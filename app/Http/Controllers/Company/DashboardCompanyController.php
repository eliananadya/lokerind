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
use Illuminate\Support\Facades\Mail;
use App\Models\Candidates;
use App\Models\Feedback;
use App\Models\FeedbackApplication;
use App\Mail\ApplicationStatusUpdated;

class DashboardCompanyController extends Controller
{
    /**
     * Display company dashboard
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            // Get company data
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return redirect()->route('company.profile.create')
                    ->with('info', 'Lengkapi profil perusahaan Anda terlebih dahulu.');
            }
            return view('company.dashboard', compact('company'));
        } catch (\Exception $e) {
            Log::error('Dashboard index error', [
                'message' => $e->getMessage()
            ]);

            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }

    /**
     * ✅ GET STATS
     */
    public function getStats()
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $totalJobs = JobPostings::where('companies_id', $company->id)->count();

            $totalApplicants = Applications::whereHas('jobPosting', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            })->count();

            $accepted = Applications::whereHas('jobPosting', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            })->where('status', 'Accepted')->count();

            // ✅ FIXED: Count from job_postings where verification_status = 'pending'
            $pending = JobPostings::where('companies_id', $company->id)
                ->where('verification_status', 'pending')
                ->count();

            Log::info('Dashboard stats loaded', [
                'company_id' => $company->id,
                'total_jobs' => $totalJobs,
                'total_applicants' => $totalApplicants,
                'accepted' => $accepted,
                'pending_verification' => $pending
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_jobs' => $totalJobs,
                    'total_applicants' => $totalApplicants,
                    'accepted' => $accepted,
                    'pending' => $pending
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading stats', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load stats'
            ], 500);
        }
    }

    /**
     * ✅ GET ALL JOBS
     */
    public function getAllJobs(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Company not found'], 404);
            }

            //  Check and close expired jobs
            JobPostings::where('companies_id', $company->id)
                ->where('status', 'Open')
                ->get()
                ->each(function ($job) {
                    $job->checkAndAutoClose();
                });

            $query = JobPostings::where('companies_id', $company->id)
                ->with(['city', 'industry', 'applications']);

            // Search
            if ($request->has('search') && $request->search) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // ✅ BARU: Filter by verification status
            if ($request->has('verification_status') && $request->verification_status) {
                $query->where('verification_status', $request->verification_status);
            }

            // Sort
            $sort = $request->get('sort', 'newest');
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $jobs = $query->paginate(10);

            $jobs->getCollection()->transform(function ($job) {
                $job->total_applicants = $job->applications->count();
                $job->slot_available = $job->slot ?? 0;
                $job->verification_status = $job->verification_status ?? 'Pending';
                return $job;
            });

            Log::info('Jobs loaded for company', [
                'company_id' => $company->id,
                'total' => $jobs->total(),
                'page' => $jobs->currentPage(),
                'verification_filter' => $request->verification_status
            ]);

            return response()->json([
                'success' => true,
                'data' => $jobs->items(),
                'pagination' => [
                    'current_page' => $jobs->currentPage(),
                    'last_page' => $jobs->lastPage(),
                    'per_page' => $jobs->perPage(),
                    'total' => $jobs->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading jobs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to load jobs'], 500);
        }
    }

    public function getPendingApplicants(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Company not found'], 404);
            }

            $query = Applications::with(['candidate.user', 'jobPosting.company', 'jobPosting.city'])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })
                ->where('status', 'Applied');

            $applicants = $query->orderBy('created_at', 'desc')->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $applicants->items(),
                'pagination' => [
                    'current_page' => $applicants->currentPage(),
                    'last_page' => $applicants->lastPage(),
                    'per_page' => $applicants->perPage(),
                    'total' => $applicants->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pending applicants', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }
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
     * ✅ GET ACCEPTED APPLICANTS
     */
    public function getAcceptedApplicants(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Company not found'], 404);
            }

            $query = Applications::with(['candidate.user', 'jobPosting.company', 'jobPosting.city'])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })
                ->where('status', 'Accepted');

            $applicants = $query->orderBy('updated_at', 'desc')->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $applicants->items(),
                'pagination' => [
                    'current_page' => $applicants->currentPage(),
                    'last_page' => $applicants->lastPage(),
                    'per_page' => $applicants->perPage(),
                    'total' => $applicants->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading accepted applicants', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }

    /**
     * ✅ GET REJECTED APPLICANTS
     */
    public function getRejectedApplicants(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Company not found'], 404);
            }

            $query = Applications::with(['candidate.user', 'jobPosting.company', 'jobPosting.city'])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })
                ->where('status', 'Rejected');

            $applicants = $query->orderBy('updated_at', 'desc')->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $applicants->items(),
                'pagination' => [
                    'current_page' => $applicants->currentPage(),
                    'last_page' => $applicants->lastPage(),
                    'per_page' => $applicants->perPage(),
                    'total' => $applicants->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading rejected applicants', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }
    /**
     * ✅ GET INVITED APPLICANTS
     */
    public function getInvitedApplicants(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $applications = Applications::with(['candidate.user', 'jobPosting.city'])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })
                ->where('status', 'invited')
                ->latest()
                ->paginate(10);

            // ✅ Debug log
            \Log::info('Invited Applications:', [
                'count' => $applications->count(),
                'data' => $applications->toArray()
            ]);

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
            \Log::error('Error loading invited applicants: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getInterviewedApplicants(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            Log::info('Loading interviewed applicants for company: ' . $company->id);

            $query = Applications::with([
                'candidate.user',
                'candidate.skills',
                'jobPosting.city',
                'jobPosting.company'
            ])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id)
                        ->where('has_interview', true); // ✅ Filter job yang ada interview
                })
                ->whereIn('status', ['Interview', 'Accepted', 'Finished']); // ✅ Status yang sudah interview

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('candidate.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $applications = $query->latest('updated_at')->paginate(10);

            Log::info('Found ' . $applications->total() . ' interviewed applicants');

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
            Log::error('Error loading interviewed applicants: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getNotInterviewedApplicants(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            Log::info('Loading not interviewed applicants for company: ' . $company->id);

            // ✅ DEBUG: Cek total applications untuk company ini
            $totalApps = Applications::whereHas('jobPosting', function ($q) use ($company) {
                $q->where('companies_id', $company->id);
            })->count();
            Log::info('Total applications for company: ' . $totalApps);

            // ✅ DEBUG: Cek jobs dengan has_interview = true
            $jobsWithInterview = JobPostings::where('companies_id', $company->id)
                ->where('has_interview', true)
                ->count();
            Log::info('Jobs with interview enabled: ' . $jobsWithInterview);

            // ✅ DEBUG: Cek applications dengan status Applied/Reviewed/Pending
            $appsWithStatus = Applications::whereHas('jobPosting', function ($q) use ($company) {
                $q->where('companies_id', $company->id);
            })
                ->whereIn('status', ['Applied', 'Reviewed', 'Pending'])
                ->count();
            Log::info('Applications with status Applied/Reviewed/Pending: ' . $appsWithStatus);

            // ✅ Query utama
            $query = Applications::with([
                'candidate.user',
                'candidate.skills',
                'jobPosting.city',
                'jobPosting.company'
            ])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id)
                        ->where('has_interview', true); // ✅ Atau ubah jadi ->where('has_interview', 1)
                })
                ->whereIn('status', ['Applied', 'Reviewed', 'Pending']);

            // ✅ DEBUG: Cek SQL query
            Log::info('SQL Query: ' . $query->toSql());
            Log::info('Bindings: ' . json_encode($query->getBindings()));

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('candidate.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $applications = $query->latest('applied_at')->paginate(10);

            Log::info('Found ' . $applications->total() . ' not interviewed applicants');

            // ✅ DEBUG: Log data pertama jika ada
            if ($applications->count() > 0) {
                Log::info('First application: ' . json_encode($applications->first()));
            }

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
            Log::error('Error loading not interviewed applicants: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function finished(Request $request)
    {
        try {
            // Log 1: Check user
            $user = Auth::user();
            Log::info('User authenticated', ['user_id' => $user->id]);

            // Log 2: Check company
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                Log::error('Company not found for user', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            Log::info('Company found', [
                'company_id' => $company->id,
                'company_name' => $company->name
            ]);

            // Log 3: Check applications query
            Log::info('Starting query for finished applications', [
                'company_id' => $company->id,
                'status' => 'Finished'
            ]);

            // Test query tanpa pagination dulu
            $query = Applications::with(['candidate.user', 'jobPosting.company'])
                ->whereHas('jobPosting', function ($query) use ($company) {
                    $query->where('companies_id', $company->id);
                })
                ->where('status', 'Finished');

            // Log SQL query
            Log::info('SQL Query', [
                'query' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Execute query
            $applicationsCount = $query->count();
            Log::info('Applications count', ['count' => $applicationsCount]);

            $applications = $query->orderBy('updated_at', 'desc')->paginate(10);

            Log::info('Applications loaded successfully', [
                'total' => $applications->total(),
                'current_page' => $applications->currentPage(),
                'per_page' => $applications->perPage()
            ]);

            // Log sample data
            if ($applications->count() > 0) {
                Log::info('Sample application data', [
                    'first_item' => $applications->first()->toArray()
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $applications->items(),
                'pagination' => [
                    'current_page' => $applications->currentPage(),
                    'last_page' => $applications->lastPage(),
                    'total' => $applications->total()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading finished applications', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load finished applications',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }
    /**
     * ✅ ACCEPT APPLICANT
     */
    public function acceptApplicant($id)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $application = Applications::whereHas('jobPosting', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            })->findOrFail($id);

            $application->status = 'Accepted';
            $application->save();

            Log::info('Applicant accepted', [
                'application_id' => $id,
                'company_id' => $company->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Applicant accepted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error accepting applicant', [
                'application_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to accept applicant'
            ], 500);
        }
    }
    public function sendEmail(Request $request)
    {
        try {
            $request->validate([
                'application_id' => 'required|exists:applications,id',
                'subject' => 'required|string|max:255',
                'message' => 'required|string'
            ]);

            $application = Applications::with(['candidate.user', 'jobPosting.company'])
                ->findOrFail($request->application_id);

            $candidate = $application->candidate;
            $user = $candidate->user;
            $company = $application->jobPosting->company;

            // Send email
            Mail::send([], [], function ($message) use ($request, $user, $company) {
                $message->to($user->email, $user->name)
                    ->subject($request->subject)
                    ->from(config('mail.from.address'), $company->name)
                    ->html(nl2br(e($request->message)));
            });

            Log::info('Email sent to candidate', [
                'application_id' => $request->application_id,
                'recipient' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * ✅ REJECT APPLICANT
     */
    public function rejectApplicant($id)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $application = Applications::whereHas('jobPosting', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            })->findOrFail($id);

            $application->status = 'Rejected';
            $application->save();

            Log::info('Applicant rejected', [
                'application_id' => $id,
                'company_id' => $company->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Applicant rejected successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error rejecting applicant', [
                'application_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject applicant'
            ], 500);
        }
    }

    /**
     * ✅ GET APPLICATION DETAIL
     */
    public function getApplicationDetail($id)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Company not found'], 404);
            }

            $application = Applications::with([
                'candidate.user',
                'candidate.skills',
                'candidate.portofolios',
                'jobPosting.company',
                'jobPosting.city'
            ])
                ->whereHas('jobPosting', function ($query) use ($company) {
                    $query->where('companies_id', $company->id);
                })
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $application
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting application detail', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }


    /**
     * ✅ UPDATE APPLICATION STATUS
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        try {
            Log::info('=== START UPDATE APPLICATION STATUS ===');
            Log::info('Application ID: ' . $id);
            Log::info('Request Data: ' . json_encode($request->all()));

            $user = Auth::user();
            if (!$user) {
                Log::error('User not authenticated');
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $company = Companies::where('user_id', $user->id)->first();
            if (!$company) {
                Log::error('Company not found for user: ' . $user->id);
                return response()->json([
                    'success' => false,
                    'message' => 'Perusahaan tidak ditemukan'
                ], 404);
            }

            // Get application with eager loading
            $application = Applications::with(['jobPosting', 'candidate.user'])
                ->whereHas('jobPosting', function ($query) use ($company) {
                    $query->where('companies_id', $company->id);
                })
                ->find($id);

            if (!$application) {
                Log::error('Application not found: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Lamaran tidak ditemukan'
                ], 404);
            }

            $job = $application->jobPosting;
            if (!$job) {
                Log::error('Job posting not found for application: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Lowongan tidak ditemukan'
                ], 404);
            }

            Log::info('Job ID: ' . $job->id . ', Status: ' . $job->status . ', Has Interview: ' . ($job->has_interview ? 'Yes' : 'No'));

            // CHECK IF JOB IS CLOSED
            if ($job->status === 'Closed') {
                Log::warning('Job is closed, cannot update');
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah status lamaran karena lowongan sudah ditutup.'
                ], 403);
            }

            // ✅ VALIDATION - Support both old and new status values
            $validator = \Validator::make($request->all(), [
                'status' => 'required|in:Selection,Accepted,Rejected,Applied,Reviewed,Interview,Withdrawn,Finished,invited',
                'message' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed: ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();
            Log::info('Validated Data: ' . json_encode($validatedData));

            // ✅ VALIDASI TAMBAHAN - Selection only for jobs with interview
            if ($request->status === 'Selection' && !$job->has_interview) {
                Log::warning('Selection not allowed for job without interview');
                return response()->json([
                    'success' => false,
                    'message' => 'Status Selection tidak diperbolehkan untuk lowongan tanpa interview.'
                ], 422);
            }

            // ✅ SLOT MANAGEMENT - Handle Accepted status
            $oldStatus = $application->status;
            $newStatus = $validatedData['status'];

            // UPDATE APPLICATION STATUS
            Log::info('Before update - Current status: ' . $application->status);

            // ✅ PERBAIKAN: Pastikan message di-trim dan dibersihkan
            $messageToSave = isset($validatedData['message']) && trim($validatedData['message']) !== ''
                ? trim($validatedData['message'])
                : null;

            $updateData = [
                'status' => $newStatus
            ];

            // Only update message if it's not null
            if ($messageToSave !== null) {
                $updateData['message'] = $messageToSave;
            }

            $updated = $application->update($updateData);

            if (!$updated) {
                Log::error('Failed to update application');
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status lamaran'
                ], 500);
            }

            // Refresh the model to get updated data
            $application->refresh();

            Log::info('After update - New status: ' . $application->status);
            Log::info('=== UPDATE SUCCESS ===');

            // ✅ STATUS MESSAGES
            $statusMessages = [
                'Selection' => 'Kandidat berhasil dipilih untuk tahap seleksi/interview!',
                'Accepted' => 'Kandidat berhasil diterima!',
                'Rejected' => 'Kandidat berhasil ditolak.',
                'Applied' => 'Status berhasil diubah ke Applied',
                'Reviewed' => 'Status berhasil diubah ke Reviewed',
                'Interview' => 'Status berhasil diubah ke Interview',
                'Withdrawn' => 'Status berhasil diubah ke Withdrawn',
                'Finished' => 'Status berhasil diubah ke Finished',
                'invited' => 'Kandidat berhasil diundang'
            ];

            return response()->json([
                'success' => true,
                'message' => $statusMessages[$newStatus] ?? 'Status berhasil diperbarui',
                'status' => $application->status,
                'data' => [
                    'application_id' => $application->id,
                    'new_status' => $application->status,
                    'message' => $application->message,
                    'remaining_slots' => $job->slot
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('=== MODEL NOT FOUND ERROR ===');
            Log::error('Message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('=== VALIDATION ERROR ===');
            Log::error('Validation errors: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== EXCEPTION ERROR ===');
            Log::error('Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    // File: app/Http/Controllers/Company/DashboardCompanyController.php

    // Tambahkan di dalam class DashboardCompanyController

    public function getCandidateRatingDetail($candidateId)
    {
        try {
            $candidate = Candidates::with([
                'user',
                'skills',
                'preferredCities',
                'preferredIndustries',
                'days',
                'portofolios'
            ])->findOrFail($candidateId);

            // ✅ Ambil semua aplikasi kandidat yang sudah selesai dengan rating
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

            // ✅ Hitung average rating
            $averageRating = $applications->avg('rating_candidates');

            // ✅ Rating breakdown (berapa banyak rating 1-5)
            $ratingBreakdown = [
                5 => $applications->where('rating_candidates', 5)->count(),
                4 => $applications->where('rating_candidates', 4)->count(),
                3 => $applications->where('rating_candidates', 3)->count(),
                2 => $applications->where('rating_candidates', 2)->count(),
                1 => $applications->where('rating_candidates', 1)->count(),
            ];

            // ✅ Ambil semua feedback yang diberikan perusahaan
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

            return response()->json([
                'success' => true,
                'data' => [
                    'candidate' => $candidate,
                    'average_rating' => round($averageRating ?? 0, 1),
                    'total_ratings' => $applications->count(),
                    'rating_breakdown' => $ratingBreakdown,
                    'feedback_counts' => $feedbackCounts,
                    'reviews' => $applications->map(function ($app) {
                        return [
                            'id' => $app->id,
                            'company_name' => $app->jobPosting->company->name ?? 'Unknown',
                            'job_title' => $app->jobPosting->title ?? '-',
                            'rating' => $app->rating_candidates,
                            'review' => $app->review_candidate,
                            'date' => $app->updated_at->format('d M Y'),
                            'feedbacks' => $app->feedbackApplications->map(function ($fa) {
                                return $fa->feedback->name ?? '-';
                            })
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting candidate rating detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kandidat'
            ], 500);
        }
    }
}
