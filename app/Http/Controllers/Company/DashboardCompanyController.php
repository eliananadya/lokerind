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
                Log::warning('Company not found for user', ['user_id' => $user->id]);
                return redirect()->route('company.profile.create')
                    ->with('info', 'Lengkapi profil perusahaan Anda terlebih dahulu.');
            }

            Log::info('Dashboard accessed', ['company_id' => $company->id, 'user_id' => $user->id]);

            return view('company.dashboard', compact('company'));
        } catch (\Exception $e) {
            Log::error('Dashboard index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
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
            Log::info('Loading stats', ['user_id' => $user->id]);

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

            $pending = Applications::whereHas('jobPosting', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            })->whereIn('status', ['Applied', 'Reviewed', 'Interview'])->count();

            Log::info('Stats loaded successfully', [
                'total_jobs' => $totalJobs,
                'total_applicants' => $totalApplicants,
                'accepted' => $accepted,
                'pending' => $pending
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
            Log::info('Loading all jobs', [
                'user_id' => $user->id,
                'search' => $request->search,
                'status' => $request->status,
                'sort' => $request->sort
            ]);

            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $query = JobPostings::where('companies_id', $company->id)
                ->with(['city', 'industry', 'typeJobs', 'applications']);

            // Search
            if ($request->has('search') && $request->search) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Sorting
            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            $jobs = $query->paginate(10);

            // ✅ AMBIL LANGSUNG DARI KOLOM 'slot' DI DATABASE
            $jobs->getCollection()->transform(function ($job) {
                $job->total_applicants = $job->applications->count();
                // ✅ Slot langsung dari database
                $job->slot_available = $job->slot ?? 0;
                return $job;
            });

            Log::info('Jobs loaded successfully', [
                'total' => $jobs->total(),
                'current_page' => $jobs->currentPage()
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

            return response()->json([
                'success' => false,
                'message' => 'Failed to load jobs'
            ], 500);
        }
    }
    /**
     * ✅ GET PENDING APPLICANTS
     */
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

    public function getPendingApplicants(Request $request)
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

            Log::info('Loading pending applicants', ['company_id' => $company->id]);

            $query = Applications::with([
                'candidate.user',
                'jobPosting.company',
                'jobPosting.city'
            ])
                ->whereHas('jobPosting', function ($q) use ($company) {
                    $q->where('companies_id', $company->id);
                })
                ->whereIn('status', ['Applied', 'Reviewed', 'Interview', 'Pending']);

            $applicants = $query->orderBy('applied_at', 'desc')->paginate(10);

            Log::info('Pending applicants loaded', ['total' => $applicants->total()]);

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
            Log::error('Error loading pending applicants', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load pending applicants'
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
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $query = Applications::with([
                'candidate.user',
                'jobPosting.company',
                'jobPosting.city'
            ])
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
            Log::error('Error loading accepted applicants', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load accepted applicants'
            ], 500);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            $query = Applications::with([
                'candidate.user',
                'jobPosting.company',
                'jobPosting.city'
            ])
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
            Log::error('Error loading rejected applicants', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load rejected applicants'
            ], 500);
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

    // ✅ Get Not Interviewed Applicants (Belum Interview)
    // public function getNotInterviewedApplicants(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         $company = Companies::where('user_id', $user->id)->firstOrFail();

    //         Log::info('Loading not interviewed applicants for company: ' . $company->id);

    //         $query = Applications::with([
    //             'candidate.user',
    //             'candidate.skills',
    //             'jobPosting.city',
    //             'jobPosting.company'
    //         ])
    //             ->whereHas('jobPosting', function ($q) use ($company) {
    //                 $q->where('companies_id', $company->id)
    //                     ->where('has_interview', true);
    //             })
    //             ->whereIn('status', ['Applied', 'Reviewed', 'Pending']);

    //         // Search
    //         if ($request->has('search') && $request->search) {
    //             $search = $request->search;
    //             $query->whereHas('candidate.user', function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                     ->orWhere('email', 'like', "%{$search}%");
    //             });
    //         }

    //         $applications = $query->latest('applied_at')->paginate(10);

    //         Log::info('Found ' . $applications->total() . ' not interviewed applicants');

    //         return response()->json([
    //             'success' => true,
    //             'data' => $applications->items(),
    //             'pagination' => [
    //                 'current_page' => $applications->currentPage(),
    //                 'last_page' => $applications->lastPage(),
    //                 'per_page' => $applications->perPage(),
    //                 'total' => $applications->total(),
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Error loading not interviewed applicants: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal memuat data: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
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
                Log::warning('Company not found', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found'
                ], 404);
            }

            Log::info('Loading application detail', [
                'application_id' => $id,
                'company_id' => $company->id
            ]);

            $application = Applications::with([
                'candidate.user',
                'candidate.skills',
                'candidate.portofolios',
                'candidate.preferredCities',
                'candidate.preferredIndustries',
                'candidate.preferredTypeJobs',
                'jobPosting.company',
                'jobPosting.city'
            ])
                ->whereHas('jobPosting', function ($query) use ($company) {
                    $query->where('companies_id', $company->id);
                })
                ->findOrFail($id);

            Log::info('Application detail loaded', [
                'application_id' => $id,
                'candidate_name' => $application->candidate->user->name ?? 'Unknown'
            ]);

            return response()->json([
                'success' => true,
                'data' => $application
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Application not found', [
                'application_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error getting application detail', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load application detail: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * ✅ UPDATE APPLICATION STATUS
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:Applied,Reviewed,Interview,Accepted,Rejected,Withdrawn,Finished,invited'
            ]);

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
            })
                ->with(['jobPosting.company', 'jobPosting.city', 'candidate.user']) // ✅ Load relasi
                ->findOrFail($id);

            $oldStatus = $application->status;
            $newStatus = $request->status;

            // ✅ CEK JIKA STATUS BERUBAH MENJADI ACCEPTED
            if ($newStatus === 'Accepted' && $oldStatus !== 'Accepted') {
                $jobPosting = $application->jobPosting;

                if ($jobPosting->slot <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Slot lowongan sudah penuh'
                    ], 400);
                }

                $jobPosting->slot -= 1;
                $jobPosting->save();

                Log::info('Job posting slot decreased', [
                    'job_posting_id' => $jobPosting->id,
                    'remaining_slots' => $jobPosting->slot
                ]);
            }

            // ✅ JIKA STATUS BERUBAH DARI ACCEPTED KE STATUS LAIN, KEMBALIKAN SLOT
            if ($oldStatus === 'Accepted' && $newStatus !== 'Accepted') {
                $jobPosting = $application->jobPosting;
                $jobPosting->slot += 1;
                $jobPosting->save();

                Log::info('Job posting slot increased', [
                    'job_posting_id' => $jobPosting->id,
                    'remaining_slots' => $jobPosting->slot
                ]);
            }

            // ✅ UPDATE STATUS APPLICATION
            $application->status = $newStatus;
            $application->save();

            Log::info('Application status updated', [
                'application_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'company_id' => $company->id
            ]);

            // ✅ KIRIM EMAIL NOTIFIKASI JIKA STATUS BERUBAH
            if ($oldStatus !== $newStatus) {
                try {
                    $candidateEmail = $application->candidate->user->email;

                    Mail::to($candidateEmail)->send(
                        new ApplicationStatusUpdated($application, $oldStatus, $newStatus)
                    );

                    Log::info('Status update email sent', [
                        'application_id' => $id,
                        'email' => $candidateEmail,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus
                    ]);
                } catch (\Exception $e) {
                    // Log error tapi jangan gagalkan proses update
                    Log::error('Failed to send status update email', [
                        'application_id' => $id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate dan email notifikasi telah dikirim',
                'data' => [
                    'application' => $application,
                    'remaining_slots' => $application->jobPosting->slot
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating application status', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage()
            ], 500);
        }
    }
}
