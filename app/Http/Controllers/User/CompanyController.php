<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\Candidates;
use App\Models\Companies;
use App\Models\Industries;
use App\Models\SaveJobs;
use App\Models\Subscribes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    /**
     * Save job to candidate's saved list
     */
    public function saveJob(Request $request)
    {
        $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id'
        ]);

        $candidate = auth()->user()->candidate;

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Kandidat tidak ditemukan'
            ], 404);
        }

        $exists = SaveJobs::where('candidates_id', $candidate->id)
            ->where('job_posting_id', $request->job_posting_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Pekerjaan sudah tersimpan sebelumnya'
            ], 400);
        }

        SaveJobs::create([
            'candidates_id' => $candidate->id,
            'job_posting_id' => $request->job_posting_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pekerjaan berhasil disimpan'
        ]);
    }

    /**
     * Unsave job from candidate's saved list
     */
    public function unsaveJob(Request $request)
    {
        $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id'
        ]);

        $candidate = auth()->user()->candidate;

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Kandidat tidak ditemukan'
            ], 404);
        }

        $deleted = SaveJobs::where('candidates_id', $candidate->id)
            ->where('job_posting_id', $request->job_posting_id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Pekerjaan berhasil dihapus dari tersimpan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pekerjaan tidak ditemukan'
        ], 404);
    }

    /**
     * Subscribe to a company
     */
    public function subscribeCompany(Request $request)
    {
        try {
            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan!'
                ], 404);
            }

            $existingSubscribe = Subscribes::where('candidates_id', $candidate->id)
                ->where('companies_id', $request->company_id)
                ->first();

            if ($existingSubscribe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah subscribe perusahaan ini!'
                ], 400);
            }

            Subscribes::create([
                'candidates_id' => $candidate->id,
                'companies_id' => $request->company_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil subscribe perusahaan!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unsubscribe from a company
     */
    public function unsubscribeCompany(Request $request)
    {
        try {
            $request->validate([
                'company_id' => 'required|integer'
            ]);

            $candidate = auth()->user()->candidate;

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan!'
                ], 404);
            }

            $deleted = Subscribes::where('candidates_id', $candidate->id)
                ->where('companies_id', (int) $request->company_id)
                ->delete();

            if ($deleted > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil unsubscribe perusahaan!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data subscribe tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('UNSUB ERROR: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    /**
     * Display a listing of companies
     * ✅ FIX: Filter hanya job postings yang Approved dan Open
     */
    public function index(Request $request)
    {
        $sortBy = $request->input('sort_by', 'name_asc');

        // ✅ FIX: Eager load dengan filter Approved + Open
        $companies = Companies::with([
            'industries',
            'jobPostings' => function ($query) {
                $query->where('status', 'Open')
                    ->where('verification_status', 'Approved');
            }
        ])
            ->withCount(['jobPostings as open_jobs_count' => function ($query) {
                $query->where('status', 'Open')
                    ->where('verification_status', 'Approved');
            }]);

        // Apply sorting
        switch ($sortBy) {
            case 'name_asc':
                $companies->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $companies->orderBy('name', 'desc');
                break;
            case 'rating_desc':
                $companies->orderByDesc('avg_rating');
                break;
            case 'rating_asc':
                $companies->orderBy('avg_rating', 'asc');
                break;
            case 'jobs_desc':
                $companies->orderByDesc('open_jobs_count');
                break;
            case 'jobs_asc':
                $companies->orderBy('open_jobs_count', 'asc');
                break;
            default:
                $companies->orderBy('name', 'asc');
        }

        $companies = $companies->paginate(10)->withQueryString();
        $industries = Industries::all();

        $subscribedCompanyIds = [];
        $savedJobIds = [];

        if (auth()->check() && auth()->user()->candidate) {
            $subscribedCompanyIds = Subscribes::where('candidates_id', auth()->user()->candidate->id)
                ->pluck('companies_id')
                ->toArray();

            $savedJobIds = SaveJobs::where('candidates_id', auth()->user()->candidate->id)
                ->pluck('job_posting_id')
                ->toArray();
        }

        return view('candidates.perusahaan', compact('companies', 'industries', 'subscribedCompanyIds', 'savedJobIds'));
    }

    /**
     * Display the specified company
     * ✅ FIX: Filter job postings yang Approved, hitung review yang valid
     */
    public function show($id)
    {
        $company = Companies::with([
            'industries',
            // ✅ FIX: Filter job postings yang Approved dan Open
            'jobPostings' => function ($query) {
                $query->where('status', 'Open')
                    ->where('verification_status', 'Approved')
                    ->with([
                        'typeJobs',
                        'industry',
                        'city',
                        'jobDatess.day',
                        'skills',
                        'benefits.benefit',
                        'company'
                    ]);
            },
            'reviews.candidate'
        ])
            ->findOrFail($id);

        // Check subscription status
        $isSubscribed = false;
        if (Auth::check()) {
            $candidate = Candidates::where('user_id', Auth::id())->first();
            if ($candidate) {
                $isSubscribed = Subscribes::where('candidates_id', $candidate->id)
                    ->where('companies_id', $id)
                    ->exists();
            }
        }

        // ✅ FIX: Hitung total reviews yang valid (ada rating)
        $totalReviews = $company->reviews()
            ->whereNotNull('rating_company')
            ->where('rating_company', '>', 0)
            ->count();

        // ✅ FIX: Rating stats yang akurat
        $ratingStats = [
            5 => $company->reviews->where('rating_company', 5)->count(),
            4 => $company->reviews->where('rating_company', 4)->count(),
            3 => $company->reviews->where('rating_company', 3)->count(),
            2 => $company->reviews->where('rating_company', 2)->count(),
            1 => $company->reviews->where('rating_company', 1)->count(),
        ];

        // ✅ SERIALIZE JOB POSTINGS
        $jobPostings = $company->jobPostings->map(function ($job) use ($company) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'salary' => $job->salary,
                'type_salary' => $job->type_salary,
                'slot' => $job->slot,
                'status' => $job->status,
                'verification_status' => $job->verification_status,
                'close_recruitment' => $job->close_recruitment,
                'address' => $job->address,
                'min_age' => $job->min_age,
                'max_age' => $job->max_age,
                'min_height' => $job->min_height,
                'min_weight' => $job->min_weight,
                'gender' => $job->gender,
                'level_mandarin' => $job->level_mandarin,
                'level_english' => $job->level_english,
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at,

                'type_jobs' => $job->typeJobs ? [
                    'id' => $job->typeJobs->id,
                    'name' => $job->typeJobs->name,
                ] : null,

                'industry' => $job->industry ? [
                    'id' => $job->industry->id,
                    'name' => $job->industry->name,
                ] : null,

                'city' => $job->city ? [
                    'id' => $job->city->id,
                    'name' => $job->city->name,
                ] : null,

                'company' => [
                    'id' => $company->id,
                    'name' => $company->name,
                ],

                'job_datess' => $job->jobDatess->map(function ($jobDate) {
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

                'skills' => $job->skills->map(function ($skill) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                    ];
                })->toArray(),

                'benefits' => $job->benefits->map(function ($benefit) {
                    return [
                        'id' => $benefit->id,
                        'benefit_type' => $benefit->benefit_type,
                        'amount' => $benefit->amount,
                        'benefit' => $benefit->benefit ? [
                            'id' => $benefit->benefit->id,
                            'name' => $benefit->benefit->name,
                        ] : null,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'description' => $company->description,
                'location' => $company->location,
                'avg_rating' => $company->avg_rating,
                'created_at' => $company->created_at,
                'industries' => $company->industries ? [
                    'id' => $company->industries->id,
                    'name' => $company->industries->name,
                ] : null,
                'job_postings' => $jobPostings,
                'reviews' => $company->reviews,
            ],
            'isSubscribed' => $isSubscribed,
            'rating_stats' => $ratingStats,
            'total_reviews' => $totalReviews // ✅ FIX: Gunakan yang sudah di-filter
        ]);
    }

    /**
     * Search companies
     * ✅ FIX: Filter job postings yang Approved
     */
    public function searchCompanies(Request $request)
    {
        try {
            Log::info('Search Companies Request', $request->all());

            // ✅ FIX: Eager load dengan filter Approved
            $query = Companies::with([
                'industries',
                'jobPostings' => function ($q) {
                    $q->where('status', 'Open')
                        ->where('verification_status', 'Approved');
                }
            ])
                ->withCount(['jobPostings as open_jobs_count' => function ($q) {
                    $q->where('status', 'Open')
                        ->where('verification_status', 'Approved');
                }]);

            // Filter by name
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // Filter by industry
            if ($request->filled('industry')) {
                $query->where('industries_id', $request->industry);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'name_asc');

            switch ($sortBy) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'rating_desc':
                    $query->orderByDesc('avg_rating');
                    break;
                case 'rating_asc':
                    $query->orderBy('avg_rating', 'asc');
                    break;
                case 'jobs_desc':
                    $query->orderByDesc('open_jobs_count');
                    break;
                case 'jobs_asc':
                    $query->orderBy('open_jobs_count', 'asc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }

            Log::info('Sorting Applied', ['sort_by' => $sortBy]);

            // Paginate
            $companies = $query->paginate(10)->withQueryString();

            return response()->json([
                'success' => true,
                'companies' => $companies->items(),
                'pagination' => [
                    'current_page' => $companies->currentPage(),
                    'last_page' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total(),
                    'from' => $companies->firstItem(),
                    'to' => $companies->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching companies', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari perusahaan: ' . $e->getMessage()
            ], 500);
        }
    }
}
