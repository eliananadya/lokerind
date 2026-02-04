<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\Industries;
use App\Models\JobPostings;
use App\Models\SaveJobs;
use App\Models\TypeJobs;
use App\Models\Applications;
use App\Models\Candidates;
use App\Models\JobCandidateMatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\JobApplicationSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;

class JobPostingUserController extends Controller
{
    /**
     * ✅ DISPLAY LISTING OF JOB POSTINGS
     * Menampilkan daftar lowongan dengan binary matching
     */
    public function index(Request $request)
    {
        $appliedJobIds = [];
        $savedJobIds = [];
        $applicationMessages = [];
        $candidate = null;
        $user = Auth::user();
        $blockedCompanyUserIds = [];

        // ========================================
        // GET USER DATA & BLOCKED COMPANIES
        // ========================================
        if ($user) {
            $blockedCompanyUserIds = \App\Models\Blacklist::where('user_id', $user->id)
                ->pluck('blocked_user_id')
                ->toArray();

            Log::info('Blocked Companies', [
                'user_id' => $user->id,
                'blocked_count' => count($blockedCompanyUserIds),
                'blocked_ids' => $blockedCompanyUserIds
            ]);

            $candidate = Candidates::where('user_id', $user->id)->first();

            if ($candidate) {
                try {
                    $appliedJobIds = Applications::where('candidates_id', $candidate->id)
                        ->pluck('job_posting_id')
                        ->toArray();

                    $savedJobIds = SaveJobs::where('candidates_id', $candidate->id)
                        ->pluck('job_posting_id')
                        ->toArray();

                    $applicationMessages = Applications::where('candidates_id', $candidate->id)
                        ->whereNotNull('message')
                        ->where('message', '!=', '')
                        ->whereNotIn('message', ['', ' ', null])
                        ->pluck('message', 'job_posting_id')
                        ->toArray();

                    Log::info('Candidate Data Loaded', [
                        'candidate_id' => $candidate->id,
                        'applied_jobs_count' => count($appliedJobIds),
                        'saved_jobs_count' => count($savedJobIds),
                        'messages_count' => count($applicationMessages)
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error loading candidate data: ' . $e->getMessage());
                }
            }
        }

        // ========================================
        // LOAD JOB POSTINGS WITH SORTING SUPPORT
        // ========================================
        try {
            if ($candidate) {
                // ✅ For logged-in candidates with profile: Use Binary Matching
                $jobPostings = $this->getJobsWithBinaryMatching($candidate, $blockedCompanyUserIds, $request);
            } else {
                // ✅ For guests or users without candidate profile: Simple query with sorting
                $query = JobPostings::with(['industry', 'typeJobs', 'city', 'jobDatess.day', 'company.user', 'skills'])
                    ->where('status', 'Open')
                    ->where('verification_status', 'Approved');

                // ✅ Exclude blocked companies
                if (!empty($blockedCompanyUserIds)) {
                    $query->whereHas('company', function ($q) use ($blockedCompanyUserIds) {
                        $q->whereNotIn('user_id', $blockedCompanyUserIds);
                    });
                }

                // ✅ APPLY SORTING (Default: Terbaru)
                $sortBy = $request->get('sort_by', 'date_desc');

                switch ($sortBy) {
                    case 'name_asc':
                        $query->orderBy('title', 'asc');
                        break;

                    case 'name_desc':
                        $query->orderBy('title', 'desc');
                        break;

                    case 'date_desc':
                        $query->orderBy('created_at', 'desc');
                        break;

                    case 'date_asc':
                        $query->orderBy('created_at', 'asc');
                        break;

                    case 'salary_desc':
                        $query->orderBy('salary', 'desc');
                        break;

                    case 'salary_asc':
                        $query->orderBy('salary', 'asc');
                        break;

                    default:
                        $query->orderBy('created_at', 'desc');
                }

                Log::info('Guest/Non-Candidate Sorting Applied', [
                    'sort_by' => $sortBy
                ]);

                $jobPostings = $query->paginate(10)->withQueryString();
            }

            Log::info('Job Postings Loaded', [
                'total_jobs' => $jobPostings->total(),
                'current_page' => $jobPostings->currentPage(),
                'per_page' => $jobPostings->perPage(),
                'items_count' => $jobPostings->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading job postings: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // ✅ FIX: Return empty paginator
            $jobPostings = $this->createEmptyPaginator(10);
        }

        // ========================================
        // LOAD FILTER OPTIONS
        // ========================================
        $cities = Cities::orderBy('name', 'asc')->get();
        $industries = Industries::orderBy('name', 'asc')->get();
        $typeJobs = TypeJobs::orderBy('name', 'asc')->get();

        return view('candidates.lowongan', compact(
            'jobPostings',
            'cities',
            'industries',
            'typeJobs',
            'appliedJobIds',
            'savedJobIds',
            'applicationMessages'
        ));
    }

    /**
     * ✅ GET JOBS WITH BINARY MATCHING (6 CRITERIA)
     * Menghitung match score dengan binary (cocok=1, tidak=0)
     * HANYA MENAMPILKAN JOB YANG SUDAH APPROVED
     *
     * @param Candidates $candidate
     * @param array $blockedCompanyUserIds
     * @param Request|null $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getJobsWithBinaryMatching($candidate, $blockedCompanyUserIds = [], $request = null)
    {
        try {
            // ✅ Load candidate dengan relasi yang dibutuhkan
            $candidate->load([
                'skills',
                'preferredCities',
                'preferredTypeJobs',
                'preferredIndustries',
                'preferredDays'
            ]);

            // ✅ CEK: Apakah kandidat punya preferensi?
            $hasPreferences =
                $candidate->preferredCities->count() > 0 ||
                $candidate->preferredTypeJobs->count() > 0 ||
                $candidate->preferredIndustries->count() > 0 ||
                $candidate->skills->count() > 0 ||
                $candidate->preferredDays->count() > 0;

            Log::info('Candidate Relations Loaded', [
                'candidate_id' => $candidate->id,
                'has_preferences' => $hasPreferences,
                'skills_count' => $candidate->skills->count(),
                'cities_count' => $candidate->preferredCities->count(),
                'type_jobs_count' => $candidate->preferredTypeJobs->count(),
                'industries_count' => $candidate->preferredIndustries->count(),
                'days_count' => $candidate->preferredDays->count(),
            ]);

            // ========================================
            // LOAD ALL OPEN & APPROVED JOB POSTINGS
            // ========================================
            $query = JobPostings::with([
                'industry',
                'typeJobs',
                'city',
                'jobDatess.day',
                'company.user',
                'skills'
            ])
                ->where('status', 'Open')
                ->where('verification_status', 'Approved');

            // ✅ Exclude blocked companies
            if (!empty($blockedCompanyUserIds)) {
                $query->whereHas('company', function ($q) use ($blockedCompanyUserIds) {
                    $q->whereNotIn('user_id', $blockedCompanyUserIds);
                });

                Log::info('Filtering blocked companies in Binary Matching', [
                    'blocked_count' => count($blockedCompanyUserIds)
                ]);
            }

            $jobPostings = $query->get();

            Log::info('Jobs fetched for Binary Matching (Approved Only)', [
                'total_jobs' => $jobPostings->count(),
                'candidate_id' => $candidate->id
            ]);

            // ✅ JIKA TIDAK ADA DATA, RETURN EMPTY PAGINATOR
            if ($jobPostings->isEmpty()) {
                Log::warning('No jobs found for binary matching');
                return $this->createEmptyPaginator(10);
            }

            // ========================================
            // CALCULATE & STORE BINARY MATCHING
            // ========================================
            $jobPostings = $jobPostings->map(function ($job) use ($candidate, $hasPreferences) {
                try {
                    // Hitung dan simpan ke database
                    $match = JobCandidateMatch::storeMatch($candidate, $job);

                    // Attach match data ke job object
                    $job->match_percentage = $match->match_percentage;
                    $job->city_match = $match->city_match;
                    $job->type_job_match = $match->type_job_match;
                    $job->industry_match = $match->industry_match;
                    $job->salary_match = $match->salary_match;
                    $job->skill_match = $match->skill_match;
                    $job->day_match = $match->day_match;

                    // Alias untuk compatibility
                    $job->similarity_score = $match->match_percentage;

                    // ✅ JIKA TIDAK PUNYA PREFERENSI, SET SCORE 0 TAPI TETAP TAMPILKAN
                    if (!$hasPreferences) {
                        $job->match_percentage = 0;
                        $job->similarity_score = 0;
                    }

                    Log::debug('Job Match Calculated', [
                        'job_id' => $job->id,
                        'job_title' => $job->title,
                        'match_percentage' => $match->match_percentage,
                        'has_preferences' => $hasPreferences
                    ]);

                    return $job;
                } catch (\Exception $e) {
                    Log::error('Error calculating match for job ' . $job->id . ': ' . $e->getMessage());

                    // Set default values jika error (TETAP TAMPILKAN JOB)
                    $job->match_percentage = 0;
                    $job->city_match = 0;
                    $job->type_job_match = 0;
                    $job->industry_match = 0;
                    $job->salary_match = 0;
                    $job->skill_match = 0;
                    $job->day_match = 0;
                    $job->similarity_score = 0;

                    return $job;
                }
            });

            // ========================================
            // APPLY SORTING
            // ========================================
            $sortBy = $request ? $request->get('sort_by', 'match_desc') : 'match_desc';

            switch ($sortBy) {
                case 'name_asc':
                    $jobPostings = $jobPostings->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE)->values();
                    break;
                case 'name_desc':
                    $jobPostings = $jobPostings->sortByDesc('title', SORT_NATURAL | SORT_FLAG_CASE)->values();
                    break;
                case 'date_desc':
                    $jobPostings = $jobPostings->sortByDesc('created_at')->values();
                    break;
                case 'date_asc':
                    $jobPostings = $jobPostings->sortBy('created_at')->values();
                    break;
                case 'salary_desc':
                    $jobPostings = $jobPostings->sortByDesc('salary')->values();
                    break;
                case 'salary_asc':
                    $jobPostings = $jobPostings->sortBy('salary')->values();
                    break;
                case 'match_desc':
                    $jobPostings = $jobPostings->sortByDesc('match_percentage')->values();
                    break;
                case 'match_asc':
                    $jobPostings = $jobPostings->sortBy('match_percentage')->values();
                    break;
                default:
                    $jobPostings = $jobPostings->sortByDesc('match_percentage')->values();
            }

            Log::info('Binary Matching Sorting Applied', [
                'sort_by' => $sortBy,
                'jobs_count' => $jobPostings->count()
            ]);

            // ========================================
            // MANUAL PAGINATION WITH QUERY STRING
            // ========================================
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $offset = ($currentPage - 1) * $perPage;

            $pagedData = $jobPostings->slice($offset, $perPage)->values();

            $paginator = new LengthAwarePaginator(
                $pagedData,
                $jobPostings->count(),
                $perPage,
                $currentPage,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => request()->query()
                ]
            );

            // ✅ PRESERVE QUERY STRING
            $paginator->withQueryString();

            Log::info('Pagination Created', [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'items_count' => $paginator->count()
            ]);

            return $paginator;
        } catch (\Exception $e) {
            Log::error('Error in Binary Matching: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // ✅ FIX: Return proper empty paginator
            return $this->createEmptyPaginator(10);
        }
    }

    /**
     * ✅ CREATE EMPTY PAGINATOR (HELPER METHOD)
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function createEmptyPaginator($perPage = 10)
    {
        return new LengthAwarePaginator(
            [],
            0,
            $perPage,
            LengthAwarePaginator::resolveCurrentPage(),
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query()
            ]
        );
    }

    /**
     * ✅ SEARCH JOBS WITH BINARY MATCHING (6 CRITERIA)
     * API endpoint untuk search dengan filter
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchJobs(Request $request)
    {
        $user = Auth::user();
        $candidate = null;
        $appliedJobIds = [];
        $savedJobIds = [];
        $applicationMessages = [];
        $blockedCompanyUserIds = [];

        // ========================================
        // GET USER DATA & BLOCKED COMPANIES
        // ========================================
        if ($user) {
            $blockedCompanyUserIds = \App\Models\Blacklist::where('user_id', $user->id)
                ->pluck('blocked_user_id')
                ->toArray();

            $candidate = Candidates::where('user_id', $user->id)->first();

            if ($candidate) {
                try {
                    $appliedJobIds = Applications::where('candidates_id', $candidate->id)
                        ->pluck('job_posting_id')
                        ->toArray();

                    $savedJobIds = SaveJobs::where('candidates_id', $candidate->id)
                        ->pluck('job_posting_id')
                        ->toArray();

                    $applicationMessages = Applications::where('candidates_id', $candidate->id)
                        ->whereNotNull('message')
                        ->where('message', '!=', '')
                        ->pluck('message', 'job_posting_id')
                        ->toArray();
                } catch (\Exception $e) {
                    Log::error('Error loading candidate data in search: ' . $e->getMessage());
                }
            }
        }

        // ========================================
        // BUILD QUERY WITH FILTERS
        // ========================================
        $query = JobPostings::with([
            'industry',
            'typeJobs',
            'city',
            'jobDatess.day',
            'company.user',
            'skills',
            'days'
        ])
            ->where('status', 'Open')
            ->where('verification_status', 'Approved');

        // ✅ EXCLUDE BLOCKED COMPANIES
        if (!empty($blockedCompanyUserIds)) {
            $query->whereHas('company', function ($q) use ($blockedCompanyUserIds) {
                $q->whereNotIn('user_id', $blockedCompanyUserIds);
            });
        }

        // ✅ APPLY SEARCH FILTERS
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('city')) {
            $query->where('cities_id', $request->city);
        }

        if ($request->filled('type_job')) {
            $query->where('type_jobs_id', $request->type_job);
        }

        if ($request->filled('industry')) {
            $query->where('industries_id', $request->industry);
        }

        // ✅ GET FILTERED JOBS
        try {
            $jobs = $query->get();

            Log::info('Search Jobs - Query Results', [
                'total_jobs' => $jobs->count(),
                'filters' => $request->only(['title', 'city', 'type_job', 'industry', 'sort_by'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error executing search query: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari lowongan',
                'jobs' => [],
                'total' => 0,
                'per_page' => 10,
                'current_page' => 1,
                'appliedJobIds' => $appliedJobIds,
                'savedJobIds' => $savedJobIds,
                'applicationMessages' => $applicationMessages,
            ], 500);
        }

        // ========================================
        // APPLY BINARY MATCHING IF CANDIDATE EXISTS
        // ========================================
        if ($candidate) {
            try {
                // ✅ Load relasi yang dibutuhkan
                $candidate->load([
                    'skills',
                    'preferredCities',
                    'preferredTypeJobs',
                    'preferredIndustries',
                    'preferredDays'
                ]);

                // ✅ CEK: Apakah kandidat punya preferensi?
                $hasPreferences =
                    $candidate->preferredCities->count() > 0 ||
                    $candidate->preferredTypeJobs->count() > 0 ||
                    $candidate->preferredIndustries->count() > 0 ||
                    $candidate->skills->count() > 0 ||
                    $candidate->preferredDays->count() > 0;

                $jobs = $jobs->map(function ($job) use ($candidate, $hasPreferences) {
                    // Hitung binary match
                    $match = JobCandidateMatch::getMatchData($candidate, $job);

                    $job->match_percentage = $match['match_percentage'];
                    $job->city_match = $match['city_match'];
                    $job->type_job_match = $match['type_job_match'];
                    $job->industry_match = $match['industry_match'];
                    $job->salary_match = $match['salary_match'];
                    $job->skill_match = $match['skill_match'];
                    $job->day_match = $match['day_match'];
                    $job->similarity_score = $match['match_percentage'];

                    // ✅ JIKA TIDAK PUNYA PREFERENSI, SET SCORE 0 TAPI TETAP TAMPILKAN
                    if (!$hasPreferences) {
                        $job->match_percentage = 0;
                        $job->similarity_score = 0;
                    }

                    return $job;
                });

                Log::info('Binary Matching applied in search', [
                    'candidate_id' => $candidate->id,
                    'has_preferences' => $hasPreferences,
                    'jobs_with_match' => $jobs->count()
                ]);
            } catch (\Exception $e) {
                Log::error('Error calculating binary match in search: ' . $e->getMessage());
            }
        } else {
            // Guest user: set default match scores
            $jobs = $jobs->map(function ($job) {
                $job->match_percentage = 0;
                $job->city_match = 0;
                $job->type_job_match = 0;
                $job->industry_match = 0;
                $job->salary_match = 0;
                $job->skill_match = 0;
                $job->day_match = 0;
                $job->similarity_score = 0;
                return $job;
            });
        }

        // ========================================
        // APPLY SORTING
        // ========================================
        $sortBy = $request->get('sort_by', 'match_desc');

        switch ($sortBy) {
            case 'name_asc':
                $jobs = $jobs->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE)->values();
                break;
            case 'name_desc':
                $jobs = $jobs->sortByDesc('title', SORT_NATURAL | SORT_FLAG_CASE)->values();
                break;
            case 'date_desc':
                $jobs = $jobs->sortByDesc('created_at')->values();
                break;
            case 'date_asc':
                $jobs = $jobs->sortBy('created_at')->values();
                break;
            case 'salary_desc':
                $jobs = $jobs->sortByDesc('salary')->values();
                break;
            case 'salary_asc':
                $jobs = $jobs->sortBy('salary')->values();
                break;
            case 'match_desc':
                $jobs = $jobs->sortByDesc('match_percentage')->values();
                break;
            case 'match_asc':
                $jobs = $jobs->sortBy('match_percentage')->values();
                break;
            default:
                $jobs = $jobs->sortByDesc('match_percentage')->values();
        }

        // ========================================
        // PAGINATE RESULTS
        // ========================================
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedJobs = new LengthAwarePaginator(
            $jobs->slice($offset, $perPage)->values(),
            $jobs->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        // ========================================
        // RETURN JSON RESPONSE
        // ========================================
        return response()->json([
            'success' => true,
            'jobs' => array_map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'description' => $job->description,
                    'salary' => $job->salary,
                    'type_salary' => $job->type_salary,
                    'slot' => $job->slot,
                    'status' => $job->status,
                    'close_recruitment' => $job->close_recruitment,
                    'updated_at' => $job->updated_at,
                    'created_at' => $job->created_at,

                    // ✅ Binary Match Scores (6 CRITERIA)
                    'match_percentage' => $job->match_percentage ?? 0,
                    'similarity_score' => $job->similarity_score ?? 0,
                    'city_match' => $job->city_match ?? 0,
                    'type_job_match' => $job->type_job_match ?? 0,
                    'industry_match' => $job->industry_match ?? 0,
                    'salary_match' => $job->salary_match ?? 0,
                    'skill_match' => $job->skill_match ?? 0,
                    'day_match' => $job->day_match ?? 0,

                    // ✅ SERIALIZE RELASI
                    'company' => $job->company ? [
                        'id' => $job->company->id,
                        'name' => $job->company->name,
                    ] : null,

                    'city' => $job->city ? [
                        'id' => $job->city->id,
                        'name' => $job->city->name,
                    ] : null,

                    'type_jobs' => $job->typeJobs ? [
                        'id' => $job->typeJobs->id,
                        'name' => $job->typeJobs->name,
                    ] : null,

                    'industry' => $job->industry ? [
                        'id' => $job->industry->id,
                        'name' => $job->industry->name,
                    ] : null,

                    // ✅ SERIALIZE JOB DATES DENGAN DAY
                    'jobDatess' => $job->jobDatess->map(function ($jobDate) {
                        return [
                            'id' => $jobDate->id,
                            'date' => $jobDate->date,
                            'start_time' => $jobDate->start_time,
                            'end_time' => $jobDate->end_time,
                            'day' => $jobDate->day ? [
                                'id' => $jobDate->day->id,
                                'name' => $jobDate->day->name,
                            ] : null,
                        ];
                    })->toArray(),
                ];
            }, $paginatedJobs->items()),
            'total' => $paginatedJobs->total(),
            'per_page' => $paginatedJobs->perPage(),
            'current_page' => $paginatedJobs->currentPage(),
            'last_page' => $paginatedJobs->lastPage(),
            'from' => $paginatedJobs->firstItem(),
            'to' => $paginatedJobs->lastItem(),
            'appliedJobIds' => $appliedJobIds,
            'savedJobIds' => $savedJobIds,
            'applicationMessages' => $applicationMessages,
            'blocked_companies_count' => count($blockedCompanyUserIds),
        ]);
    }

    /**
     * ✅ SAVE JOB TO CANDIDATE'S SAVED LIST
     */
    public function saveJob(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:job_postings,id',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Anda harus login terlebih dahulu.'], 401);
        }

        $candidate = Candidates::where('user_id', $user->id)->first();

        if (!$candidate) {
            return response()->json(['message' => 'Profil kandidat tidak ditemukan.'], 404);
        }

        $existingSave = SaveJobs::where('candidates_id', $candidate->id)
            ->where('job_posting_id', $request->job_id)
            ->first();

        if ($existingSave) {
            return response()->json(['message' => 'Pekerjaan sudah disimpan sebelumnya.'], 400);
        }

        SaveJobs::create([
            'candidates_id' => $candidate->id,
            'job_posting_id' => $request->job_id,
        ]);

        return response()->json(['message' => 'Pekerjaan berhasil disimpan.'], 200);
    }

    /**
     * ✅ DISPLAY THE SPECIFIED JOB POSTING
     */
    public function show($id)
    {
        $job = JobPostings::with([
            'company',
            'city',
            'industry',
            'typeJobs',
            'jobDatess.day',
            'benefits.benefit',
            'skills'
        ])->find($id);

        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        $job->benefits = $job->benefits->unique('benefit_id');
        $job->skills = $job->skills->unique('id');

        $hasApplied = false;
        $isSaved = false;

        $user = Auth::user();
        if ($user) {
            $candidate = Candidates::where('user_id', $user->id)->first();
            if ($candidate) {
                $hasApplied = Applications::where('candidates_id', $candidate->id)
                    ->where('job_posting_id', $id)
                    ->exists();

                $isSaved = SaveJobs::where('candidates_id', $candidate->id)
                    ->where('job_posting_id', $id)
                    ->exists();
            }
        }

        $jobData = $job->toArray();
        $jobData['jobDatess'] = $job->jobDatess->map(function ($jobDate) {
            return [
                'id' => $jobDate->id,
                'date' => $jobDate->date,
                'start_time' => $jobDate->start_time,
                'end_time' => $jobDate->end_time,
                'day' => $jobDate->day ? [
                    'id' => $jobDate->day->id,
                    'name' => $jobDate->day->name,
                ] : null,
            ];
        })->toArray();

        return response()->json([
            'job' => $jobData,
            'hasApplied' => $hasApplied,
            'isSaved' => $isSaved
        ]);
    }

    /**
     * ✅ APPLY FOR A JOB POSTING
     */
    public function applyJob(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu untuk melamar pekerjaan.'
                ], 401);
            }

            $request->validate([
                'job_posting_id' => 'required|exists:job_postings,id',
                'message' => 'nullable|string|max:500'
            ]);

            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus melengkapi profil kandidat terlebih dahulu.'
                ], 422);
            }

            $existingApplication = Applications::where('candidates_id', $candidate->id)
                ->where('job_posting_id', $request->job_posting_id)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melamar pekerjaan ini sebelumnya.',
                    'status' => $existingApplication->status
                ], 422);
            }

            $jobPosting = JobPostings::find($request->job_posting_id);
            if (!$jobPosting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lowongan pekerjaan tidak ditemukan.'
                ], 404);
            }

            $application = Applications::create([
                'candidates_id' => $candidate->id,
                'job_posting_id' => $request->job_posting_id,
                'status' => 'Pending',
                'message' => null,
                'applied_at' => now(),
                'rating_candidates' => 0,
                'rating_company' => 0,
                'review_candidate' => '',
                'review_company' => '',
            ]);

            Log::info('New job application created', [
                'application_id' => $application->id,
                'candidate_id' => $candidate->id,
                'job_posting_id' => $request->job_posting_id,
                'status' => 'pending'
            ]);

            try {
                Mail::to($user->email)->send(new JobApplicationSubmitted($application));

                Log::info('Application email sent', [
                    'application_id' => $application->id,
                    'email' => $user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send application email', [
                    'application_id' => $application->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lamaran berhasil dikirim! Kami telah mengirimkan konfirmasi ke email Anda.',
                'application' => [
                    'id' => $application->id,
                    'status' => $application->status,
                    'applied_at' => $application->applied_at->format('d M Y H:i')
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error applying for job', [
                'user_id' => Auth::id(),
                'job_posting_id' => $request->job_posting_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * ✅ CHECK IF USER HAS APPLIED FOR A JOB
     */
    public function checkApplication(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'hasApplied' => false,
                    'isAuthenticated' => false
                ]);
            }

            $request->validate([
                'job_posting_id' => 'required|exists:job_postings,id'
            ]);

            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'hasApplied' => false,
                    'isAuthenticated' => true,
                    'hasProfile' => false
                ]);
            }

            $application = Applications::where('candidates_id', $candidate->id)
                ->where('job_posting_id', $request->job_posting_id)
                ->first();

            return response()->json([
                'hasApplied' => $application !== null,
                'isAuthenticated' => true,
                'hasProfile' => true,
                'application' => $application ? [
                    'status' => $application->status,
                    'applied_at' => $application->applied_at->format('d M Y')
                ] : null
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking application', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'hasApplied' => false,
                'error' => 'Terjadi kesalahan saat memeriksa status lamaran.'
            ], 500);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }
}
