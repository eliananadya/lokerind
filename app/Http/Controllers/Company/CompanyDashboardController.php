<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\Companies;
use App\Models\JobPostings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyDashboardController extends Controller
{
    /**
     * Display company dashboard with job postings
     */
    public function index(Request $request)
    {
        try {
            // Get company data for logged-in user
            $company = Companies::where('user_id', Auth::id())->first();

            if (!$company) {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki profil perusahaan.');
            }

            // Get filter status from request
            $statusFilter = $request->input('status', 'all');

            // Query job postings
            $query = JobPostings::where('companies_id', $company->id)
                ->with(['city', 'industry', 'typeJobs', 'applications.candidate'])
                ->withCount('applications');

            // Apply status filter if not 'all'
            if ($statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }

            // Get job postings with pagination
            $jobPostings = $query->orderBy('created_at', 'desc')->paginate(10);

            // Count by status for filter badges
            $statusCounts = [
                'all' => JobPostings::where('companies_id', $company->id)->count(),
                'pending' => JobPostings::where('companies_id', $company->id)->where('status', 'pending')->count(),
                'open' => JobPostings::where('companies_id', $company->id)->where('status', 'open')->count(),
                'selection' => JobPostings::where('companies_id', $company->id)->where('status', 'selection')->count(),
                'finish' => JobPostings::where('companies_id', $company->id)->where('status', 'finish')->count(),
                'withdraw' => JobPostings::where('companies_id', $company->id)->where('status', 'withdraw')->count(),
                'cancel' => JobPostings::where('companies_id', $company->id)->where('status', 'cancel')->count(),
            ];

            return view('company.dashboard', compact('company', 'jobPostings', 'statusFilter', 'statusCounts'));
        } catch (\Exception $e) {
            Log::error('Error in CompanyDashboardController@index: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }
    // ✅ Get Withdrawn Applicants (Mengundurkan Diri)
    public function getWithdrawnApplicants(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            Log::info('Loading withdrawn applicants for company: ' . $company->id);

            $query = Applications::with([
                'candidate.user',
                'candidate.skills',
                'jobPosting.city',
                'jobPosting.company'
            ])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })
                ->where('status', 'Withdrawn'); // ✅ Filter status Withdrawn

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('candidate.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $applications = $query->latest('updated_at')->paginate(10);

            Log::info('Found ' . $applications->total() . ' withdrawn applicants');

            return response()->json([
                'success' => true,
                'data' => $applications->items(),
                'pagination' => [
                    'current_page' => $applications->currentPage(),
                    'last_page' => $applications->lastPage(),
                    'per_page' => $applications->perPage(),
                    'total' => $applications->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading withdrawn applicants: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Update Get Badge Counts (tambahkan withdrawn)
    public function getBadgeCounts()
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $counts = [
                'semua' => JobPostings::where('companies_id', $company->id)->count(),
                'pelamar' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })->where('invited_by_company', true)->count(),
                'pending' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })->where('status', 'Applied')->count(),

                'interviewed' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id)
                        ->where('has_interview', true);
                })->whereIn('status', ['Interview', 'Accepted', 'Finished'])->count(),

                'notInterviewed' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id)
                        ->where('has_interview', true);
                })->whereIn('status', ['Applied', 'Reviewed'])->count(),

                'accepted' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })->where('status', 'Accepted')->count(),

                'rejected' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })->where('status', 'Rejected')->count(),

                // ✅ Baru: Withdrawn
                'withdrawn' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })->where('status', 'Withdrawn')->count(),

                'finished' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })->where('status', 'Finished')->count(),
            ];

            return response()->json([
                'success' => true,
                'counts' => $counts
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading badge counts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }

    /**
     * Get applicants for specific job posting (AJAX)
     */
    public function getApplicants($jobPostingId)
    {
        try {
            $company = Companies::where('user_id', Auth::id())->first();

            if (!$company) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Verify job posting belongs to this company
            $jobPosting = JobPostings::where('id', $jobPostingId)
                ->where('companies_id', $company->id)
                ->first();

            if (!$jobPosting) {
                return response()->json(['error' => 'Job posting not found'], 404);
            }

            // Get applicants with their details
            $applicants = Applications::where('job_posting_id', $jobPostingId)
                ->with([
                    'candidate.skills',
                    'candidate.portofolios',
                    'candidate.prefferedCities.cities',
                    'candidate.preferredIndustries'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'jobPosting' => [
                    'id' => $jobPosting->id,
                    'title' => $jobPosting->title,
                    'slot' => $jobPosting->slot,
                    'status' => $jobPosting->status,
                ],
                'applicants' => $applicants
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CompanyDashboardController@getApplicants: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Accept/Verify applicant
     */
    public function acceptApplicant(Request $request)
    {
        try {
            $request->validate([
                'application_id' => 'required|exists:applications,id',
            ]);

            DB::beginTransaction();

            $company = Companies::where('user_id', Auth::id())->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Get application with job posting
            $application = Applications::with('jobPosting')->find($request->application_id);

            // Verify job posting belongs to this company
            if ($application->jobPosting->companies_id !== $company->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Check if already accepted
            if ($application->status === 'accepted' || $application->status === 'verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelamar sudah diterima sebelumnya.'
                ]);
            }

            // Check if slot is available
            if ($application->jobPosting->slot <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot lowongan sudah penuh.'
                ]);
            }

            // Update application status
            $application->status = 'accepted';
            $application->save();

            // Decrease slot
            $jobPosting = $application->jobPosting;
            $jobPosting->slot = $jobPosting->slot - 1;

            // If slot reaches 0, change job status to 'finish'
            if ($jobPosting->slot <= 0) {
                $jobPosting->status = 'finish';
            } else {
                // Update to selection if not already
                if ($jobPosting->status === 'open' || $jobPosting->status === 'pending') {
                    $jobPosting->status = 'selection';
                }
            }

            $jobPosting->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pelamar berhasil diterima!',
                'newSlot' => $jobPosting->slot,
                'newStatus' => $jobPosting->status
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CompanyDashboardController@acceptApplicant: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menerima pelamar.'
            ], 500);
        }
    }

    /**
     * Reject applicant
     */
    public function rejectApplicant(Request $request)
    {
        try {
            $request->validate([
                'application_id' => 'required|exists:applications,id',
            ]);

            $company = Companies::where('user_id', Auth::id())->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Get application with job posting
            $application = Applications::with('jobPosting')->find($request->application_id);

            // Verify job posting belongs to this company
            if ($application->jobPosting->companies_id !== $company->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Check if already rejected
            if ($application->status === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelamar sudah ditolak sebelumnya.'
                ]);
            }

            // Update application status
            $application->status = 'rejected';
            $application->save();

            return response()->json([
                'success' => true,
                'message' => 'Pelamar berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CompanyDashboardController@rejectApplicant: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak pelamar.'
            ], 500);
        }
    }

    /**
     * Filter job postings by status (AJAX)
     */
    public function filterByStatus(Request $request)
    {
        try {
            $company = Companies::where('user_id', Auth::id())->first();

            if (!$company) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $status = $request->input('status', 'all');

            $query = JobPostings::where('companies_id', $company->id)
                ->with(['city', 'industry', 'typeJobs'])
                ->withCount('applications');

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            $jobPostings = $query->orderBy('created_at', 'desc')->paginate(10);

            return response()->json([
                'success' => true,
                'jobPostings' => $jobPostings
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CompanyDashboardController@filterByStatus: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
