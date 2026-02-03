<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\Candidates;
use App\Models\Cities;
use App\Models\Companies;
use App\Models\JobPostings;
use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CandidateRecommendationController extends Controller
{
    /**
     * Display candidate recommendation page
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            // Get all company's job postings for filter
            $jobPostings = JobPostings::where('companies_id', $company->id)
                ->where('status', 'Open')
                ->orderBy('created_at', 'desc')
                ->get();

            // Get all skills and cities for filters
            $skills = Skills::orderBy('name')->get();
            $cities = Cities::orderBy('name')->get();

            return view('company.candidates.recommendations', compact('company', 'jobPostings', 'skills', 'cities'));
        } catch (\Exception $e) {
            Log::error('Recommendation page error: ' . $e->getMessage());
            return redirect()->route('company.dashboard')->with('error', 'Terjadi kesalahan saat memuat halaman.');
        }
    }

    /**
     * Get recommended candidates based on job posting and filters
     */
    public function getCandidates(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $jobPostingId = $request->input('job_posting_id');
            $skillFilter = $request->input('skills', []);
            $cityFilter = $request->input('city_id');
            $genderFilter = $request->input('gender');
            $search = $request->input('search');

            // Start building query
            $query = Candidates::with(['skills', 'user', 'prefferedCities', 'portofolios'])
                ->whereHas('user', function ($q) {
                    $q->where('email_verified_at', '!=', null);
                });

            // If job posting is selected, calculate skill match
            if ($jobPostingId) {
                $jobPosting = JobPostings::with('skills')->findOrFail($jobPostingId);
                $requiredSkillIds = $jobPosting->skills->pluck('id')->toArray();

                if (!empty($requiredSkillIds)) {
                    // Filter candidates who have at least one matching skill
                    $query->whereHas('skills', function ($q) use ($requiredSkillIds) {
                        $q->whereIn('skills.id', $requiredSkillIds);
                    });
                }

                // Additional job requirements filtering
                if ($jobPosting->gender !== 'All') {
                    $query->where('gender', $jobPosting->gender);
                }

                if ($jobPosting->cities_id) {
                    $query->whereHas('prefferedCities', function ($q) use ($jobPosting) {
                        $q->where('cities.id', $jobPosting->cities_id);
                    });
                }
            }

            // Apply additional filters
            if (!empty($skillFilter)) {
                $query->whereHas('skills', function ($q) use ($skillFilter) {
                    $q->whereIn('skills.id', $skillFilter);
                });
            }

            if ($cityFilter) {
                $query->whereHas('prefferedCities', function ($q) use ($cityFilter) {
                    $q->where('cities.id', $cityFilter);
                });
            }

            if ($genderFilter && $genderFilter !== 'All') {
                $query->where('gender', $genderFilter);
            }

            if ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Exclude candidates who already applied to this job
            if ($jobPostingId) {
                $query->whereDoesntHave('applications', function ($q) use ($jobPostingId) {
                    $q->where('job_posting_id', $jobPostingId);
                });
            }

            // Get candidates with pagination
            $candidates = $query->paginate(12);

            // Calculate skill match percentage for each candidate
            if ($jobPostingId && isset($requiredSkillIds) && !empty($requiredSkillIds)) {
                $candidatesWithMatch = [];

                foreach ($candidates->items() as $candidate) {
                    $candidateSkillIds = $candidate->skills->pluck('id')->toArray();
                    $matchingSkills = array_intersect($requiredSkillIds, $candidateSkillIds);

                    $candidate->skill_match_percentage = count($requiredSkillIds) > 0
                        ? round((count($matchingSkills) / count($requiredSkillIds)) * 100)
                        : 0;
                    $candidate->matching_skills_count = count($matchingSkills);

                    $candidatesWithMatch[] = $candidate;
                }

                // Sort by match percentage (highest first)
                usort($candidatesWithMatch, function ($a, $b) {
                    return $b->skill_match_percentage <=> $a->skill_match_percentage;
                });

                // Replace items with sorted ones
                $candidates = new \Illuminate\Pagination\LengthAwarePaginator(
                    $candidatesWithMatch,
                    $candidates->total(),
                    $candidates->perPage(),
                    $candidates->currentPage(),
                    ['path' => $candidates->path()]
                );
            }

            return response()->json([
                'success' => true,
                'data' => $candidates
            ]);
        } catch (\Exception $e) {
            Log::error('Get candidates error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat kandidat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get job posting skills for auto-filling filters
     */
    public function getJobSkills($jobId)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $jobPosting = JobPostings::where('id', $jobId)
                ->where('companies_id', $company->id)
                ->with('skills')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'skills' => $jobPosting->skills->pluck('id')->toArray(),
                    'job_title' => $jobPosting->title
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get job skills error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat skill lowongan'
            ], 404);
        }
    }

    /**
     * Get detailed candidate information
     */
    public function getCandidateDetail($id)
    {
        try {
            $candidate = Candidates::with([
                'user',
                'skills',
                'prefferedCities',
                'prefferedIndustries',
                'preferredTypeJobs',
                'portofolios',
                'applications' => function ($query) {
                    $query->where('status', 'Accepted')->with('jobPosting.company');
                }
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            Log::error('Get candidate detail error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Kandidat tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Send invitation to candidate
     */
    public function sendInvitation(Request $request)
    {
        try {
            $request->validate([
                'candidate_id' => 'required|exists:candidates,id',
                'job_posting_id' => 'required|exists:job_postings,id',
                'message' => 'nullable|string|max:1000'
            ]);

            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            // Verify job posting belongs to company
            $jobPosting = JobPostings::where('id', $request->job_posting_id)
                ->where('companies_id', $company->id)
                ->firstOrFail();

            // Check if invitation already exists
            $existingApplication = Applications::where('candidates_id', $request->candidate_id)
                ->where('job_posting_id', $request->job_posting_id)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kandidat sudah pernah melamar atau diundang untuk lowongan ini.'
                ], 400);
            }

            // Create invitation (application record with invited status)
            $application = Applications::create([
                'candidates_id' => $request->candidate_id,
                'job_posting_id' => $request->job_posting_id,
                'status' => 'Invited',
                'message' => $request->message,
                'applied_at' => now(),
                'invited_by_company' => '1',
                'invited_at' => now()
            ]);

            Log::info('Invitation sent', [
                'company_id' => $company->id,
                'candidate_id' => $request->candidate_id,
                'job_posting_id' => $request->job_posting_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Undangan berhasil dikirim ke kandidat!',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            Log::error('Send invitation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim undangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
