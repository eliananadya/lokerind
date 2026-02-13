<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Mail\JobInvitationMail;
use App\Models\Applications;
use App\Models\Blacklist;
use App\Models\Candidates;
use App\Models\Companies;
use App\Models\JobPostings;
use App\Models\JobCandidateMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Feedback;
use App\Models\FeedbackApplication;

class CandidateMatchController extends Controller
{
    /**
     * ✅ BINARY MATCHING (Content-Based Filtering 1:0)
     * Menggantikan Cosine Similarity dengan Binary Matching
     */
    private function calculateBinaryMatch($candidate, $jobPosting)
    {
        $totalRequirements = 0; // |L| - Total requirement dari job
        $matchedRequirements = 0; // |K ∩ L| - Requirement yang cocok

        // Tracking per kriteria
        $cityMatch = 0;
        $typeJobMatch = 0;
        $industryMatch = 0;
        $salaryMatch = 0;
        $skillMatch = 0;
        $dayMatch = 0;

        try {
            // 1. CITY MATCH (1 requirement)
            $totalRequirements++;
            $candidateCities = $candidate->preferredCities->pluck('id')->toArray();
            if (in_array($jobPosting->cities_id, $candidateCities)) {
                $matchedRequirements++;
                $cityMatch = 1;
            }

            // 2. TYPE JOB MATCH (1 requirement)
            $totalRequirements++;
            $candidateTypeJobs = $candidate->preferredTypeJobs->pluck('id')->toArray();
            if (in_array($jobPosting->type_jobs_id, $candidateTypeJobs)) {
                $matchedRequirements++;
                $typeJobMatch = 1;
            }

            // 3. INDUSTRY MATCH (1 requirement)
            $totalRequirements++;
            $candidateIndustries = $candidate->preferredIndustries->pluck('id')->toArray();
            if (in_array($jobPosting->industries_id, $candidateIndustries)) {
                $matchedRequirements++;
                $industryMatch = 1;
            }

            // 4. SALARY MATCH (1 requirement jika job punya salary)
            if ($jobPosting->salary) {
                $totalRequirements++;
                if ($candidate->min_salary && $jobPosting->salary >= $candidate->min_salary) {
                    $matchedRequirements++;
                    $salaryMatch = 1;
                }
            }

            // 5. SKILL MATCH (N requirements - setiap skill job dihitung)
            $jobSkills = $jobPosting->skills->pluck('id')->toArray();
            $candidateSkills = $candidate->skills->pluck('id')->toArray();

            if (!empty($jobSkills)) {
                $totalRequirements += count($jobSkills);
                $commonSkills = array_intersect($candidateSkills, $jobSkills);
                $matchedRequirements += count($commonSkills);
                $skillMatch = count($commonSkills) > 0 ? 1 : 0;
            }

            // 6. DAY MATCH (N requirements - setiap hari job dihitung)
            $jobDays = $jobPosting->jobDatess->pluck('days_id')->filter()->unique()->toArray();
            $candidateDays = $candidate->days->pluck('id')->toArray();

            if (!empty($jobDays)) {
                $totalRequirements += count($jobDays);
                $commonDays = array_intersect($candidateDays, $jobDays);
                $matchedRequirements += count($commonDays);
                $dayMatch = count($commonDays) > 0 ? 1 : 0;
            }
        } catch (\Exception $e) {
            \Log::error('Error calculating match: ' . $e->getMessage());
        }

        // Hitung persentase
        $matchPercentage = $totalRequirements > 0
            ? ($matchedRequirements / $totalRequirements) * 100
            : 0;

        return [
            'city_match' => $cityMatch,
            'type_job_match' => $typeJobMatch,
            'industry_match' => $industryMatch,
            'salary_match' => $salaryMatch,
            'skill_match' => $skillMatch,
            'day_match' => $dayMatch,
            'match_percentage' => round($matchPercentage, 2),
            'total_requirements' => $totalRequirements,
            'matched_requirements' => $matchedRequirements,
        ];
    }

    /**
     * Display matching candidates
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return redirect()->route('company.dashboard')
                    ->with('error', 'Profil perusahaan tidak ditemukan.');
            }

            // Get blacklisted candidate IDs
            $blacklistedCandidateIds = Blacklist::where('user_id', $user->id)
                ->pluck('blocked_user_id')
                ->toArray();

            $blacklistedCandidateIds = Candidates::whereIn('user_id', $blacklistedCandidateIds)
                ->pluck('id')
                ->toArray();

            // Get all ACTIVE and APPROVED job postings
            $jobPostings = JobPostings::where('companies_id', $company->id)
                ->where('status', 'Open')
                ->where('verification_status', 'Approved')
                ->with(['skills', 'industry', 'typeJobs', 'city', 'applications', 'jobDatess'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get selected job posting
            $selectedJobId = $request->get('job_id');
            $selectedJob = $selectedJobId
                ? $jobPostings->firstWhere('id', $selectedJobId)
                : $jobPostings->first();

            $matchingCandidates = collect();

            if ($selectedJob) {
                // Get candidates yang sudah apply
                $appliedCandidateIds = Applications::where('job_posting_id', $selectedJob->id)
                    ->pluck('candidates_id')
                    ->toArray();

                // ✅ Get ALL candidates tanpa filter apapun
                $allCandidates = Candidates::with([
                    'skills',
                    'preferredIndustries',
                    'preferredTypeJobs',
                    'preferredCities',
                    'days',
                    'user',
                    'portofolios'
                ])->get();

                Log::info('=== CANDIDATE MATCHING DEBUG ===', [
                    'total_candidates_in_db' => $allCandidates->count(),
                    'blacklisted_count' => count($blacklistedCandidateIds),
                    'applied_count' => count($appliedCandidateIds),
                ]);

                // Filter manual untuk debugging yang lebih baik
                $candidates = $allCandidates->filter(function ($candidate) use ($blacklistedCandidateIds, $appliedCandidateIds) {
                    $isBlacklisted = in_array($candidate->id, $blacklistedCandidateIds);
                    $hasApplied = in_array($candidate->id, $appliedCandidateIds);

                    if ($isBlacklisted) {
                        Log::debug("Candidate {$candidate->name} (ID: {$candidate->id}) is blacklisted");
                    }
                    if ($hasApplied) {
                        Log::debug("Candidate {$candidate->name} (ID: {$candidate->id}) has already applied");
                    }

                    return !$isBlacklisted && !$hasApplied;
                });

                Log::info('Candidates after filtering:', [
                    'available_count' => $candidates->count()
                ]);

                // ✅ Calculate match menggunakan JobCandidateMatch model
                foreach ($candidates as $candidate) {
                    try {
                        // Gunakan method dari JobCandidateMatch
                        $matchData = JobCandidateMatch::getMatchData($candidate, $selectedJob);

                        // Simpan ke database
                        JobCandidateMatch::updateOrCreate(
                            [
                                'candidates_id' => $candidate->id,
                                'job_posting_id' => $selectedJob->id,
                            ],
                            $matchData
                        );

                        // Attach match data ke candidate object
                        $candidate->match_score = $matchData['match_percentage'];
                        $candidate->city_match = $matchData['city_match'];
                        $candidate->type_job_match = $matchData['type_job_match'];
                        $candidate->industry_match = $matchData['industry_match'];
                        $candidate->salary_match = $matchData['salary_match'];
                        $candidate->skill_match = $matchData['skill_match'];
                        $candidate->day_match = $matchData['day_match'];

                        // ✅ TAMPILKAN SEMUA KANDIDAT (tidak ada filter minimum)
                        $matchingCandidates->push($candidate);

                        Log::info('Match Calculated for ' . $candidate->name, [
                            'match_percentage' => $matchData['match_percentage'] . '%',
                            'details' => $matchData
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error calculating match for candidate ' . $candidate->id . ': ' . $e->getMessage());
                        continue;
                    }
                }

                // Sort by match percentage (highest first)
                $matchingCandidates = $matchingCandidates->sortByDesc('match_score');

                Log::info('Final Matching Results:', [
                    'total_matching' => $matchingCandidates->count()
                ]);
            }

            // Pagination
            $perPage = 12;
            $currentPage = $request->get('page', 1);
            $matchingCandidates = new \Illuminate\Pagination\LengthAwarePaginator(
                $matchingCandidates->forPage($currentPage, $perPage),
                $matchingCandidates->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('company.candidates.match', compact(
                'matchingCandidates',
                'jobPostings',
                'selectedJob'
            ));
        } catch (\Exception $e) {
            Log::error('Candidate Match Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('company.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getCandidateRatingDetail($candidateId)
    {
        try {
            Log::info('=== START GET CANDIDATE RATING DETAIL (MATCH) ===', [
                'candidate_id' => $candidateId
            ]);

            $candidate = Candidates::with([
                'user',
                'skills',
                'preferredCities',
                'preferredIndustries',
                'days',
                'portofolios'
            ])->find($candidateId);

            if (!$candidate) {
                Log::error('Candidate not found', ['candidate_id' => $candidateId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Kandidat tidak ditemukan'
                ], 404);
            }

            // Ambil aplikasi dengan rating
            $applications = Applications::where('candidates_id', $candidateId)
                ->whereIn('status', ['Accepted', 'Finished'])
                ->whereNotNull('rating_candidates')
                ->with([
                    'jobPosting.company.user',
                    'jobPosting.typeJobs',
                    'jobPosting.city',
                    'feedbackApplications' => function ($q) {
                        $q->where('given_by', 'company')->with('feedback');
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->get();

            $averageRating = $applications->avg('rating_candidates');

            $ratingBreakdown = [
                5 => $applications->where('rating_candidates', 5)->count(),
                4 => $applications->where('rating_candidates', 4)->count(),
                3 => $applications->where('rating_candidates', 3)->count(),
                2 => $applications->where('rating_candidates', 2)->count(),
                1 => $applications->where('rating_candidates', 1)->count(),
            ];

            $candidateFeedbacks = Feedback::where('for', 'candidate')->get();
            $feedbackCounts = [];

            foreach ($candidateFeedbacks as $feedback) {
                $count = FeedbackApplication::where('feedback_id', $feedback->id)
                    ->where('given_by', 'company')
                    ->whereHas('application', function ($q) use ($candidateId) {
                        $q->where('candidates_id', $candidateId);
                    })
                    ->count();

                if ($count > 0) {
                    $feedbackCounts[] = [
                        'name' => $feedback->name,
                        'count' => $count
                    ];
                }
            }

            $reviews = $applications->map(function ($app) {
                return [
                    'id' => $app->id,
                    'company_name' => $app->jobPosting && $app->jobPosting->company
                        ? $app->jobPosting->company->name
                        : 'Unknown',
                    'job_title' => $app->jobPosting ? $app->jobPosting->title : '-',
                    'rating' => $app->rating_candidates,
                    'review' => $app->review_candidate,
                    'date' => $app->updated_at->format('d M Y'),
                    'feedbacks' => $app->feedbackApplications->map(function ($fa) {
                        return $fa->feedback ? $fa->feedback->name : '-';
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'candidate' => $candidate,
                    'average_rating' => round($averageRating ?? 0, 1),
                    'total_ratings' => $applications->count(),
                    'rating_breakdown' => $ratingBreakdown,
                    'feedback_counts' => $feedbackCounts,
                    'reviews' => $reviews
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== ERROR GET CANDIDATE RATING DETAIL (MATCH) ===', [
                'candidate_id' => $candidateId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data rating kandidat: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get candidate detail via AJAX
     */
    public function getCandidateDetail($candidateId)
    {
        try {
            Log::info('=== START GET CANDIDATE DETAIL ===', ['candidate_id' => $candidateId]);

            $candidate = Candidates::with([
                'user',
                'skills',
                'preferredCities',
                'preferredIndustries',
                'preferredTypeJobs',
                'days',
                'portofolios'
            ])->find($candidateId);

            if (!$candidate) {
                Log::error('Candidate not found', ['candidate_id' => $candidateId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Kandidat tidak ditemukan'
                ], 404);
            }

            Log::info('Candidate found', ['name' => $candidate->name]);

            // Format data untuk response
            $candidateData = [
                'id' => $candidate->id,
                'candidates_id' => $candidate->id, // Tambahkan ini untuk compatibility
                'name' => $candidate->name ?? 'N/A',
                'gender' => $candidate->gender ?? 'N/A',
                'birth_date' => $candidate->birth_date ?? null,
                'phone_number' => $candidate->phone_number ?? null,
                'description' => $candidate->description ?? null,
                'min_height' => $candidate->min_height ?? null,
                'min_weight' => $candidate->min_weight ?? null,
                'min_salary' => $candidate->min_salary ?? null,
                'level_english' => $candidate->level_english ?? 'N/A',
                'level_mandarin' => $candidate->level_mandarin ?? 'N/A',
                'avg_rating' => $candidate->avg_rating ?? 0,
                'point' => $candidate->point ?? 0,
                'user' => [
                    'email' => $candidate->user->email ?? 'N/A',
                    'photo' => $candidate->photo ?? null // Ambil dari candidates table
                ],
                'skills' => $candidate->skills->map(function ($skill) {
                    return ['name' => $skill->name];
                })->toArray(),
                'preferred_industries' => $candidate->preferredIndustries->map(function ($industry) {
                    return ['name' => $industry->name];
                })->toArray(),
                'preferred_type_jobs' => $candidate->preferredTypeJobs->map(function ($typeJob) {
                    return ['name' => $typeJob->name];
                })->toArray(),
                'preferred_cities' => $candidate->preferredCities->map(function ($city) {
                    return ['name' => $city->name];
                })->toArray(),
                'days' => $candidate->days->map(function ($day) {
                    return ['name' => $day->name];
                })->toArray(),
                'portofolios' => $candidate->portofolios->map(function ($portfolio) {
                    return [
                        'title' => $portfolio->caption ?? 'Portfolio',
                        'file' => $portfolio->file ?? null
                    ];
                })->toArray()
            ];

            return response()->json([
                'success' => true,
                'candidate' => $candidateData
            ]);
        } catch (\Exception $e) {
            Log::error('=== ERROR GET CANDIDATE DETAIL ===', [
                'candidate_id' => $candidateId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Invite candidate to apply
     */
    public function inviteCandidate(Request $request, $candidateId)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'job_posting_id' => 'required|exists:job_postings,id',
                'message' => 'nullable|string|max:500'
            ]);

            Log::info('Invite Candidate Request:', [
                'candidate_id' => $candidateId,
                'job_posting_id' => $validated['job_posting_id'],
                'company_id' => Auth::id()
            ]);

            // Cek apakah kandidat ada
            $candidate = Candidates::with('user')->findOrFail($candidateId);

            // Cek apakah job posting ada dan milik company yang login
            $jobPosting = JobPostings::with(['company', 'city'])
                ->where('id', $validated['job_posting_id'])
                ->where('companies_id', Auth::user()->company->id)
                ->firstOrFail();

            // Cek apakah sudah pernah diundang atau apply
            $existingApplication = Applications::where('candidates_id', $candidateId)
                ->where('job_posting_id', $validated['job_posting_id'])
                ->first();

            if ($existingApplication) {
                if ($existingApplication->invited_by_company) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kandidat sudah pernah diundang untuk lowongan ini'
                    ], 422);
                }

                if (in_array($existingApplication->status, ['Finished', 'Rejected', 'Invited', 'Withdrawn', 'Pending', 'Accepted'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kandidat sudah melamar untuk lowongan ini'
                    ], 422);
                }
            }

            // Buat application baru dengan status invited
            DB::beginTransaction();

            $application = Applications::create([
                'candidates_id' => $candidateId,
                'job_posting_id' => $validated['job_posting_id'],
                'status' => 'invited',
                'message' => $validated['message'] ?? null,
                'invited_by_company' => true,
                'invited_at' => now(),
                'applied_at' => now(),
            ]);

            // ✅ Kirim email notifikasi
            try {
                $this->sendInvitationNotification($candidate, $jobPosting, $validated['message']);
                Log::info('Invitation email sent successfully to: ' . $candidate->user->email);
            } catch (\Exception $mailError) {
                Log::error('Failed to send invitation email:', [
                    'error' => $mailError->getMessage(),
                    'candidate_email' => $candidate->user->email
                ]);
            }

            DB::commit();

            Log::info('Candidate invited successfully:', [
                'application_id' => $application->id,
                'candidate_id' => $candidateId,
                'job_posting_id' => $validated['job_posting_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Undangan berhasil dikirim ke kandidat',
                'data' => [
                    'application_id' => $application->id,
                    'candidate_name' => $candidate->name,
                    'job_title' => $jobPosting->title
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Model not found:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Kandidat atau lowongan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invite Candidate Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim undangan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send invitation notification to candidate
     */
    private function sendInvitationNotification($candidate, $jobPosting, $message)
    {
        try {
            Mail::to($candidate->user->email)
                ->send(new JobInvitationMail($candidate, $jobPosting, $message));

            Log::info('Invitation email queued for: ' . $candidate->user->email);
        } catch (\Exception $e) {
            Log::error('Send Invitation Email Error:', [
                'error' => $e->getMessage(),
                'candidate_email' => $candidate->user->email
            ]);
            throw $e;
        }
    }
}
