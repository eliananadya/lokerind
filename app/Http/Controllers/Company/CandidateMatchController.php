<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Mail\JobInvitationMail;
use App\Models\Applications;
use App\Models\Blacklist;
use App\Models\Candidates;
use App\Models\Companies;
use App\Models\JobPostings;
use App\Models\JobCandidateMatch; // ✅ TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CandidateMatchController extends Controller
{
    /**
     * ✅ BINARY MATCHING (Content-Based Filtering 1:0)
     * Menggantikan Cosine Similarity dengan Binary Matching
     */
    private function calculateBinaryMatch($candidate, $jobPosting)
    {
        // Ambil preferensi kandidat
        $candidateCities = $candidate->preferredCities->pluck('id')->toArray();
        $candidateTypeJobs = $candidate->preferredTypeJobs->pluck('id')->toArray();
        $candidateIndustries = $candidate->preferredIndustries->pluck('id')->toArray();

        // Binary matching: 1 = cocok, 0 = tidak cocok
        $cityMatch = in_array($jobPosting->cities_id, $candidateCities) ? 1 : 0;
        $typeJobMatch = in_array($jobPosting->type_jobs_id, $candidateTypeJobs) ? 1 : 0;
        $industryMatch = in_array($jobPosting->industries_id, $candidateIndustries) ? 1 : 0;

        // Hitung total match
        $totalMatch = $cityMatch + $typeJobMatch + $industryMatch;
        $totalCriteria = 3; // city, type_job, industry

        // Persentase kecocokan
        $matchPercentage = ($totalMatch / $totalCriteria) * 100;

        return [
            'total_score' => round($matchPercentage, 2),
            'city_match' => $cityMatch,
            'type_job_match' => $typeJobMatch,
            'industry_match' => $industryMatch,
            'match_percentage' => round($matchPercentage, 2),
            'breakdown' => [
                'city' => $cityMatch ? 100 : 0,
                'type_job' => $typeJobMatch ? 100 : 0,
                'industry' => $industryMatch ? 100 : 0,
            ]
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

            // Get company
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return redirect()->route('company.dashboard')
                    ->with('error', 'Profil perusahaan tidak ditemukan.');
            }

            \Log::info('Company ID: ' . $company->id);
            \Log::info('User ID: ' . $user->id);

            // ✅ Get blacklisted candidate IDs
            $blacklistedCandidateIds = Blacklist::where('user_id', $user->id)
                ->pluck('blocked_user_id')
                ->toArray();

            \Log::info('Blacklisted User IDs: ', $blacklistedCandidateIds);

            // ✅ Convert blocked_user_id ke candidates_id
            $blacklistedCandidateIds = Candidates::whereIn('user_id', $blacklistedCandidateIds)
                ->pluck('id')
                ->toArray();

            \Log::info('Blacklisted Candidate IDs: ', $blacklistedCandidateIds);

            // Get all ACTIVE and APPROVED job postings
            $jobPostings = JobPostings::where('companies_id', $company->id)
                ->where('status', 'Open')
                ->where('verification_status', 'Approved') // ✅ HANYA APPROVED
                ->with(['skills', 'industry', 'typeJobs', 'city', 'applications'])
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Total FILTERED Jobs (Open + Approved): ' . $jobPostings->count());

            // Get selected job posting
            $selectedJobId = $request->get('job_id');
            $selectedJob = null;

            if ($selectedJobId) {
                $selectedJob = $jobPostings->firstWhere('id', $selectedJobId);
            } else {
                $selectedJob = $jobPostings->first();
            }

            \Log::info('Selected Job ID: ' . ($selectedJob ? $selectedJob->id : 'NULL'));

            $matchingCandidates = collect();

            if ($selectedJob) {
                // ✅ Get candidates yang sudah apply
                $appliedCandidateIds = Applications::where('job_posting_id', $selectedJob->id)
                    ->pluck('candidates_id')
                    ->toArray();

                \Log::info('Applied Candidate IDs: ', $appliedCandidateIds);

                // ✅ Get all candidates (exclude blacklisted AND already applied)
                $candidates = Candidates::with([
                    'skills',
                    'preferredIndustries',
                    'preferredTypeJobs',
                    'preferredCities',
                    'user',
                    'portofolios'
                ])
                    ->whereNotIn('id', $blacklistedCandidateIds) // ✅ Exclude blacklisted
                    ->whereNotIn('id', $appliedCandidateIds)     // ✅ Exclude already applied
                    ->get();

                \Log::info('Total Candidates (after filtering): ' . $candidates->count());

                // ✅ CALCULATE BINARY MATCH untuk setiap kandidat
                foreach ($candidates as $candidate) {
                    try {
                        // Hitung binary match
                        $matchResult = $this->calculateBinaryMatch($candidate, $selectedJob);

                        // Simpan ke database
                        $matchRecord = JobCandidateMatch::updateOrCreate(
                            [
                                'candidates_id' => $candidate->id,
                                'job_posting_id' => $selectedJob->id,
                            ],
                            [
                                'city_match' => $matchResult['city_match'],
                                'type_job_match' => $matchResult['type_job_match'],
                                'industry_match' => $matchResult['industry_match'],
                                'match_percentage' => $matchResult['match_percentage'],
                            ]
                        );

                        // Attach match data ke candidate object
                        $candidate->match_score = $matchResult['total_score'];
                        $candidate->match_percentage = $matchResult['match_percentage'];
                        $candidate->city_match = $matchResult['city_match'];
                        $candidate->type_job_match = $matchResult['type_job_match'];
                        $candidate->industry_match = $matchResult['industry_match'];
                        $candidate->match_breakdown = $matchResult['breakdown'];

                        // ✅ Hanya tampilkan kandidat dengan match >= 33% (minimal 1 dari 3 kriteria cocok)
                        if ($candidate->match_score >= 33.33) {
                            $matchingCandidates->push($candidate);
                        }

                        \Log::info('Candidate Match Calculated', [
                            'candidate_id' => $candidate->id,
                            'candidate_name' => $candidate->name,
                            'match_score' => $matchResult['total_score'],
                            'city_match' => $matchResult['city_match'],
                            'type_job_match' => $matchResult['type_job_match'],
                            'industry_match' => $matchResult['industry_match']
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Error calculating match for candidate ' . $candidate->id . ': ' . $e->getMessage());
                        continue;
                    }
                }

                // Sort by match score (highest first)
                $matchingCandidates = $matchingCandidates->sortByDesc('match_score');

                \Log::info('Total Matching Candidates (score >= 33%): ' . $matchingCandidates->count());
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

            // ✅ Statistics dengan Binary Match
            $allMatches = $matchingCandidates->items();
            $stats = [
                'total_matches' => $matchingCandidates->total(),
                'perfect_matches' => collect($allMatches)->where('match_score', '=', 100)->count(), // 100% (3/3 cocok)
                'good_matches' => collect($allMatches)->where('match_score', '=', 66.67)->count(), // 66.67% (2/3 cocok)
                'fair_matches' => collect($allMatches)->where('match_score', '=', 33.33)->count(), // 33.33% (1/3 cocok)
                'excellent_matches' => collect($allMatches)->where('match_score', '=', 100)->count(),    // Alias untuk perfect
            ];

            return view('company.candidates.match', compact(
                'matchingCandidates',
                'jobPostings',
                'selectedJob',
                'stats'
            ));
        } catch (\Exception $e) {
            \Log::error('Candidate Match Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->route('company.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat data kandidat: ' . $e->getMessage());
        }
    }

    /**
     * Get candidate detail via AJAX
     */
    public function getCandidateDetail($candidateId)
    {
        try {
            \Log::info('=== START getCandidateDetail ===');
            \Log::info('Candidate ID: ' . $candidateId);

            if (!is_numeric($candidateId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID kandidat tidak valid'
                ], 400);
            }

            $candidate = Candidates::with([
                'skills',
                'preferredIndustries',
                'preferredTypeJobs',
                'preferredCities',
                'days',
                'user',
                'portofolios'
            ])->find($candidateId);

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kandidat tidak ditemukan'
                ], 404);
            }

            $response = [
                'success' => true,
                'candidate' => [
                    'id' => $candidate->id,
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
                        'email' => optional($candidate->user)->email ?? 'N/A'
                    ],
                    'skills' => $candidate->skills->map(function ($skill) {
                        return ['name' => $skill->name ?? 'N/A'];
                    })->toArray(),
                    'preferred_industries' => $candidate->preferredIndustries->map(function ($industry) {
                        return ['name' => $industry->name ?? 'N/A'];
                    })->toArray(),
                    'preferred_type_jobs' => $candidate->preferredTypeJobs->map(function ($type) {
                        return ['name' => $type->name ?? 'N/A'];
                    })->toArray(),
                    'preferred_cities' => $candidate->preferredCities->map(function ($city) {
                        return ['name' => $city->name ?? 'N/A'];
                    })->toArray(),
                    'portofolios' => $candidate->portofolios->map(function ($portfolio) {
                        return [
                            'title' => $portfolio->caption ?? 'Portfolio',
                            'description' => $portfolio->caption ?? null,
                            'url' => $portfolio->file ? asset('storage/' . $portfolio->file) : null
                        ];
                    })->toArray()
                ]
            ];

            \Log::info('Response prepared successfully');
            return response()->json($response, 200);
        } catch (\Exception $e) {
            \Log::error('Exception in getCandidateDetail: ' . $e->getMessage());
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

                if ($existingApplication->status === 'applied') {
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
