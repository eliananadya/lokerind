<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\Candidates;
use App\Models\Companies;
use App\Models\Feedback;
use App\Models\FeedbackApplication;
use App\Models\Reports;
use App\Models\HistoryPoint;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return view('candidates.riwayat', [
                    'reports' => collect([]),
                    'applications' => collect([]),
                    'invitations' => collect([]),
                    'feedbackApplicationsFromCompany' => collect([]),
                    'feedbackApplicationsGivenByCandidate' => collect([]),
                    'ratingsReceived' => collect([]),
                    'allItems' => collect([]),
                    'feedbacks' => Feedback::all(),
                    'statusFilter' => null,
                    'reportedApplicationIds' => [],
                    'blockedCompanies' => collect([]),
                    'statusOptions' => $this->getDefaultStatuses(),
                    'ratingBreakdown' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
                    'totalInvitations' => 0,
                    'myReports' => collect([]),
                    'feedbackSummary' => ['average_rating' => 0, 'feedback_counts' => []] // ✅ TAMBAHKAN INI
                ])->with('info', 'Lengkapi profil Anda untuk melihat riwayat lengkap.');
            }

            $myReports = Reports::where('user_id', $user->id)
                ->with([
                    'application' => function ($q) {
                        $q->with([
                            'jobPosting' => function ($jq) {
                                $jq->with([
                                    'company',
                                    'industry',
                                    'typeJobs',
                                    'city',
                                    'jobDatess' => function ($dateQ) {
                                        $dateQ->with('day')->orderBy('date', 'asc');
                                    }
                                ]);
                            }
                        ]);
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'myreports_page');

            $reportedApplicationIds = Reports::where('user_id', $user->id)
                ->pluck('application_id')
                ->toArray();

            $blockedCompanies = Blacklist::where('user_id', $user->id)
                ->with(['blockedUser.company'])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'blocked_page');

            $statusFilter = $request->get('status');
            $totalInvitations = Applications::where('candidates_id', $candidate->id)
                ->where('invited_by_company', 1)
                ->count();

            $invitations = Applications::where('candidates_id', $candidate->id)
                ->where('invited_by_company', 1)
                ->with([
                    'jobPosting.company',
                    'jobPosting.typeJobs',
                    'jobPosting.city',
                    'jobPosting.industry',
                    'jobPosting.skills',
                    'jobPosting.benefits.benefit',
                    'jobPosting.jobDatess.day'
                ])
                ->orderBy('invited_at', 'desc')
                ->paginate(10, ['*'], 'invitations_page');

            $baseQuery = Applications::where('candidates_id', $candidate->id);
            if ($statusFilter) {
                $baseQuery->where('status', $statusFilter);
            }

            $applications = (clone $baseQuery)
                ->with([
                    'jobPosting.company',
                    'jobPosting.typeJobs',
                    'jobPosting.city'
                ])
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'apps_page');

            $reports = (clone $baseQuery)
                ->where(function ($q) {
                    $q->whereNotNull('rating_candidates')
                        ->orWhereNotNull('review_candidate')
                        ->orWhereHas('feedbackApplications', function ($fq) {
                            $fq->where('given_by', 'company');
                        });
                })
                ->with([
                    'jobPosting.company',
                    'jobPosting.typeJobs',
                    'jobPosting.city',
                    'feedbackApplications' => function ($q) {
                        $q->where('given_by', 'company')->with('feedback');
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'reports_page');

            $ratingsReceived = (clone $baseQuery)
                ->whereNotNull('rating_candidates')
                ->with([
                    'jobPosting.company',
                    'jobPosting.typeJobs',
                    'jobPosting.city'
                ])
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'ratings_page');

            // ✅ FEEDBACK FROM COMPANY - Grouped by application
            $feedbackQuery = FeedbackApplication::whereHas('application', function ($q) use ($candidate, $statusFilter) {
                $q->where('candidates_id', $candidate->id);
                if ($statusFilter) {
                    $q->where('status', $statusFilter);
                }
            })
                ->where('given_by', 'company')
                ->with([
                    'application.jobPosting.company.user',
                    'application.jobPosting.typeJobs',
                    'application.jobPosting.city',
                    'feedback'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            // ✅ Group by application_id
            $groupedFeedbacks = $feedbackQuery->groupBy('application_id');

            $feedbackApplicationsFromCompany = $groupedFeedbacks->map(function ($feedbacks, $applicationId) {
                $firstFeedback = $feedbacks->first();

                return (object) [
                    'application' => $firstFeedback->application,
                    'feedbacks' => $feedbacks,
                    'created_at' => $feedbacks->max('created_at'),
                ];
            })->sortByDesc('created_at')->values();

            // ✅ Manual pagination untuk grouped feedback
            $perPageFeedback = 10;
            $currentPageFeedback = $request->get('feedback_page', 1);
            $feedbackApplicationsFromCompany = new \Illuminate\Pagination\LengthAwarePaginator(
                $feedbackApplicationsFromCompany->forPage($currentPageFeedback, $perPageFeedback),
                $feedbackApplicationsFromCompany->count(),
                $perPageFeedback,
                $currentPageFeedback,
                ['path' => $request->url(), 'pageName' => 'feedback_page']
            );

            $feedbackApplicationsGivenByCandidate = FeedbackApplication::whereHas('application', function ($q) use ($candidate, $statusFilter) {
                $q->where('candidates_id', $candidate->id);
                if ($statusFilter) {
                    $q->where('status', $statusFilter);
                }
            })
                ->where('given_by', 'candidate')
                ->with([
                    'application.jobPosting.company',
                    'application.jobPosting.typeJobs',
                    'feedback'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'myfeedback_page');

            $ratingStats = Applications::where('candidates_id', $candidate->id)
                ->where('status', 'Finished')
                ->whereNotNull('rating_company')
                ->select('rating_company', DB::raw('count(*) as count'))
                ->groupBy('rating_company')
                ->pluck('count', 'rating_company')
                ->toArray();

            $ratingBreakdown = [
                5 => $ratingStats[5] ?? 0,
                4 => $ratingStats[4] ?? 0,
                3 => $ratingStats[3] ?? 0,
                2 => $ratingStats[2] ?? 0,
                1 => $ratingStats[1] ?? 0,
            ];

            $allItemsCollection = $this->buildAllItemsCollection($candidate, $statusFilter);

            // ✅ Pagination untuk All Items (nama variabel berbeda)
            $perPageAll = 10;
            $currentPageAll = $request->get('all_page', 1);
            $allItems = new \Illuminate\Pagination\LengthAwarePaginator(
                $allItemsCollection->forPage($currentPageAll, $perPageAll),
                $allItemsCollection->count(),
                $perPageAll,
                $currentPageAll,
                ['path' => $request->url(), 'pageName' => 'all_page']
            );

            $feedbacks = Feedback::all();
            $statusOptions = $this->getApplicationStatuses();
            $feedbackSummary = $this->getCandidateFeedbackSummary($candidate->id);
            return view('candidates.riwayat', compact(
                'reports',
                'applications',
                'invitations',
                'feedbackApplicationsFromCompany',
                'feedbackApplicationsGivenByCandidate',
                'ratingsReceived',
                'allItems',
                'feedbacks',
                'statusOptions',
                'candidate',
                'reportedApplicationIds',
                'blockedCompanies',
                'statusFilter',
                'ratingBreakdown',
                'totalInvitations',
                'myReports',
                'feedbackSummary',
            ));
        } catch (\Exception $e) {
            Log::error('History page error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman riwayat.');
        }
    }

    private function getCandidateFeedbackSummary($candidateId)
    {
        try {
            // Hitung average rating kandidat
            $averageRating = Applications::where('candidates_id', $candidateId)
                ->whereNotNull('rating_candidates')
                ->where('rating_candidates', '>=', 1)
                ->where('rating_candidates', '<=', 5)
                ->avg('rating_candidates');

            // Ambil semua feedback untuk candidate
            $candidateFeedbacks = Feedback::where('for', 'candidate')->get();

            // Hitung total setiap feedback yang diberikan oleh company
            $feedbackCounts = [];
            foreach ($candidateFeedbacks as $feedback) {
                $count = FeedbackApplication::where('feedback_id', $feedback->id)
                    ->where('given_by', 'company')
                    ->whereHas('application', function ($q) use ($candidateId) {
                        $q->where('candidates_id', $candidateId);
                    })
                    ->count();

                $feedbackCounts[] = [
                    'name' => $feedback->name,
                    'count' => $count
                ];
            }

            return [
                'average_rating' => round($averageRating ?? 0, 1),
                'feedback_counts' => $feedbackCounts
            ];
        } catch (\Exception $e) {
            Log::error('Error getting feedback summary: ' . $e->getMessage());

            // Return default values jika error
            return [
                'average_rating' => 0,
                'feedback_counts' => []
            ];
        }
    }

    private function buildAllItemsCollection($candidate, $statusFilter = null)
    {
        $allItemsCollection = collect();

        $applicationsQuery = Applications::where('candidates_id', $candidate->id);
        if ($statusFilter) {
            $applicationsQuery->where('status', $statusFilter);
        }
        $allApplications = $applicationsQuery
            ->with(['jobPosting.company', 'jobPosting.typeJobs', 'jobPosting.city'])
            ->get()
            ->map(function ($item) {
                $item->item_type = 'application';
                $item->sort_date = $item->updated_at;
                return $item;
            });

        $reportsQuery = Applications::where('candidates_id', $candidate->id)
            ->where(function ($q) {
                $q->whereNotNull('rating_company')
                    ->orWhereNotNull('review_company');
            });
        if ($statusFilter) {
            $reportsQuery->where('status', $statusFilter);
        }
        $allReports = $reportsQuery
            ->with([
                'jobPosting.company',
                'jobPosting.typeJobs',
                'jobPosting.city'
            ])
            ->get()
            ->map(function ($item) {
                $item->item_type = 'report';
                $item->sort_date = $item->updated_at;
                return $item;
            });

        $feedbackFromCompanyQuery = FeedbackApplication::whereHas('application', function ($q) use ($candidate, $statusFilter) {
            $q->where('candidates_id', $candidate->id);
            if ($statusFilter) {
                $q->where('status', $statusFilter);
            }
        })->where('given_by', 'company');
        $allFeedbackFromCompany = $feedbackFromCompanyQuery
            ->with(['application.jobPosting.company', 'application.jobPosting.typeJobs', 'feedback'])
            ->get()
            ->map(function ($item) {
                $item->item_type = 'feedback_from_company';
                $item->sort_date = $item->created_at;
                return $item;
            });

        $myFeedbackQuery = FeedbackApplication::whereHas('application', function ($q) use ($candidate, $statusFilter) {
            $q->where('candidates_id', $candidate->id);
            if ($statusFilter) {
                $q->where('status', $statusFilter);
            }
        })->where('given_by', 'candidate');
        $allMyFeedback = $myFeedbackQuery
            ->with(['application.jobPosting.company', 'application.jobPosting.typeJobs', 'feedback'])
            ->get()
            ->map(function ($item) {
                $item->item_type = 'my_feedback';
                $item->sort_date = $item->created_at;
                return $item;
            });

        $ratingsQuery = Applications::where('candidates_id', $candidate->id)
            ->whereNotNull('rating_candidates');
        if ($statusFilter) {
            $ratingsQuery->where('status', $statusFilter);
        }
        $allRatings = $ratingsQuery
            ->with(['jobPosting.company', 'jobPosting.typeJobs', 'jobPosting.city'])
            ->get()
            ->map(function ($item) {
                $item->item_type = 'rating_received';
                $item->sort_date = $item->updated_at;
                return $item;
            });

        return $allApplications
            ->concat($allReports)
            ->concat($allFeedbackFromCompany)
            ->concat($allMyFeedback)
            ->concat($allRatings)
            ->unique('id')
            ->sortByDesc('sort_date')
            ->values();
    }

    /**
     * ✅ FIXED: Withdraw application
     * - Penalty 5 poin HANYA untuk status Accepted
     * - Status lainnya (Applied, Reviewed, invited) = 0 poin penalty
     */
    public function withdraw(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required|string|min:10|max:500'
            ], [
                'reason.required' => 'Alasan wajib diisi',
                'reason.min' => 'Alasan minimal 10 karakter',
                'reason.max' => 'Alasan maksimal 500 karakter'
            ]);

            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan.'
                ], 404);
            }

            // ✅ FIX: Ambil aplikasi dengan relasi
            $application = Applications::with('jobPosting')
                ->where('id', $id)
                ->where('candidates_id', $candidate->id)
                ->first();

            // ✅ Check if application exists
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lamaran tidak ditemukan atau bukan milik Anda.'
                ], 404);
            }

            // ✅ FIX: Cek status yang TIDAK BOLEH ditarik
            $forbiddenStatuses = ['Withdrawn', 'Finished', 'Rejected'];
            if (in_array($application->status, $forbiddenStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lamaran dengan status "' . $application->status . '" tidak dapat ditarik.'
                ], 422);
            }

            $oldStatus = $application->status;
            $oldPoint = $candidate->point ?? 0;

            // ✅ PENALTY HANYA UNTUK STATUS "Accepted"
            $penaltyPoint = 0;
            if ($oldStatus === 'Accepted') {
                $penaltyPoint = 5;
            }

            $newPoint = max(0, $oldPoint - $penaltyPoint);

            // ✅ Update application status ke Withdrawn
            $application->update([
                'status' => 'Withdrawn',
                'withdrawn_at' => now(),
                'withdraw_reason' => $request->reason
            ]);

            // ✅ Update point kandidat jika ada penalty
            if ($penaltyPoint > 0) {
                $candidate->update(['point' => $newPoint]);
            }

            // ✅ Catat history point (baik ada penalty atau tidak)
            HistoryPoint::create([
                'candidates_id' => $candidate->id,
                'application_id' => $application->id,
                'old_point' => $oldPoint,
                'new_point' => $newPoint,
                'reason' => $penaltyPoint > 0 ? 'withdraw_accepted' : 'withdraw_application'
            ]);

            Log::info('Application withdrawn successfully', [
                'application_id' => $id,
                'candidate_id' => $candidate->id,
                'old_status' => $oldStatus,
                'old_point' => $oldPoint,
                'new_point' => $newPoint,
                'penalty_applied' => $penaltyPoint,
            ]);

            // ✅ Message yang jelas
            if ($penaltyPoint > 0) {
                $message = "Lamaran berhasil ditarik dari status '{$oldStatus}'. Poin Anda dikurangi {$penaltyPoint} poin karena sudah diterima.";
            } else {
                $message = "Lamaran berhasil ditarik dari status '{$oldStatus}'. Tidak ada pengurangan poin.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'old_status' => $oldStatus,
                    'old_point' => $oldPoint,
                    'new_point' => $newPoint,
                    'penalty_applied' => $penaltyPoint,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error withdrawing application', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function acceptInvitation($id)
    {
        try {
            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Candidate not found'
                ], 404);
            }

            $application = Applications::with('jobPosting')
                ->where('id', $id)
                ->where('candidates_id', $candidate->id)
                ->where('invited_by_company', 1)
                ->where('status', 'Invited')
                ->first();

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan tidak ditemukan atau sudah diproses'
                ], 404);
            }

            $newStatus = $application->jobPosting->has_interview == 1 ? 'Interview' : 'Accepted';

            $application->update([
                'status' => $newStatus,
                'applied_at' => now()
            ]);

            Log::info('Invitation accepted', [
                'application_id' => $id,
                'candidate_id' => $candidate->id,
                'new_status' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => $newStatus === 'Interview'
                    ? 'Undangan diterima! Anda akan dihubungi untuk tahap wawancara.'
                    : 'Undangan diterima! Anda telah diterima untuk posisi ini.',
                'data' => [
                    'status' => $newStatus,
                    'has_interview' => $application->jobPosting->has_interview == 1
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error accepting invitation', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menerima undangan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectInvitation(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kandidat tidak ditemukan'
                ], 404);
            }

            $application = Applications::with('jobPosting.company')
                ->where('id', $id)
                ->where('candidates_id', $candidate->id)
                ->where('invited_by_company', 1)
                ->where('status', 'Invited')
                ->first();

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'Undangan tidak ditemukan atau sudah diproses'
                ], 404);
            }

            // ✅ Update status ke Rejected
            $application->update([
                'status' => 'Rejected',
            ]);

            Log::info('Invitation rejected', [
                'application_id' => $id,
                'candidate_id' => $candidate->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Undangan berhasil ditolak',
                'data' => [
                    'company_name' => $application->jobPosting->company->name ?? 'Company',
                    'job_title' => $application->jobPosting->title ?? 'Job'
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error rejecting invitation', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak undangan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating_company' => 'required|integer|min:1|max:5',
            'review_company' => 'nullable|string|max:1000',
            'feedbacks' => 'nullable|array',
            'feedbacks.*' => 'exists:feedback,id'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan.'
                ], 404);
            }

            $application = Applications::findOrFail($id);

            if ($application->candidates_id !== $candidate->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk memberikan rating pada aplikasi ini.'
                ], 403);
            }

            if (!in_array($application->status, ['Accepted', 'Finished'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya aplikasi dengan status Accepted atau Finished yang bisa diberi rating.'
                ], 422);
            }

            if ($application->rating_company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan rating untuk aplikasi ini.'
                ], 422);
            }

            $application->update([
                'rating_company' => $request->rating_company,
                'review_company' => $request->review_company,
            ]);

            if ($request->has('feedbacks') && is_array($request->feedbacks)) {
                foreach ($request->feedbacks as $feedbackId) {
                    FeedbackApplication::create([
                        'given_by' => 'candidate',
                        'feedback_id' => $feedbackId,
                        'application_id' => $application->id,
                    ]);
                }
            }

            $company = $application->jobPosting->company;
            $this->updateCompanyAverageRating($company->id);


            HistoryPoint::create([
                'candidates_id' => $candidate->id,
                'application_id' => $application->id,
                'reason' => 'rate_company'
            ]);

            Log::info('Candidate rated company and received points', [
                'candidate_id' => $candidate->id,
                'application_id' => $application->id,
                'rating' => $request->rating_company,
            ]);

            DB::commit();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Aplikasi tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rate company error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function rateCompany(Request $request, $id)
    {
        $request->validate([
            'rating_company' => 'required|integer|min:1|max:5',
            'review_company' => 'nullable|string|max:1000',
            'feedbacks' => 'nullable|array',
            'feedbacks.*' => 'exists:feedback,id'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan.'
                ], 404);
            }

            $application = Applications::with('jobPosting.company')
                ->where('id', $id)
                ->where('candidates_id', $candidate->id)
                ->firstOrFail();

            // ✅ Validasi: Hanya status Finished yang bisa kasih rating
            if ($application->status !== 'Finished') {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating hanya bisa diberikan untuk aplikasi dengan status "Finished".'
                ], 422);
            }
            // ✅ CEK: Apakah rating sudah pernah dihapus admin?
            $hasApprovedReportFromCompany = Reports::where('application_id', $id)
                ->where('status', 'approved')
                ->whereHas('user', function ($q) {
                    $q->where('role_id', 3); // Report DARI company (role_id = 3)
                })
                ->exists();

            // Jika company melaporkan (approved) dan rating_company sudah null, berarti dihapus admin
            if ($hasApprovedReportFromCompany && is_null($application->rating_company)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating Anda telah dihapus oleh admin karena dilaporkan perusahaan. Anda tidak dapat memberikan rating lagi.'
                ], 403);
            }
            // ✅ Validasi: Cek apakah sudah pernah kasih rating
            if ($application->rating_company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan rating untuk perusahaan ini.'
                ], 422);
            }

            // ✅ Update rating dan review
            $application->update([
                'rating_company' => $request->rating_company,
                'review_company' => $request->review_company,
            ]);

            // ✅ Simpan feedback (jika ada)
            if ($request->has('feedbacks') && is_array($request->feedbacks)) {
                foreach ($request->feedbacks as $feedbackId) {
                    FeedbackApplication::create([
                        'given_by' => 'candidate',
                        'feedback_id' => $feedbackId,
                        'application_id' => $application->id,
                    ]);
                }
            }

            // ✅ Update average rating perusahaan
            $this->updateCompanyAverageRating($application->jobPosting->companies_id);

            // ✅ TIDAK ADA TAMBAHAN POIN - Hanya catat history
            HistoryPoint::create([
                'candidates_id' => $candidate->id,
                'application_id' => $application->id,
                'reason' => 'rate_company'
            ]);

            Log::info('Candidate rated company (no point reward)', [
                'candidate_id' => $candidate->id,
                'application_id' => $application->id,
                'rating' => $request->rating_company,
            ]);

            DB::commit();

            // ✅ RESPONSE JSON YANG BENAR
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Rating Anda telah tersimpan.',
                'data' => [
                    'rating' => $request->rating_company,
                    'review' => $request->review_company,
                    'feedbacks_count' => count($request->feedbacks ?? [])
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Aplikasi tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rate company error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function updateCompanyAverageRating($companyId)
    {
        $company = Companies::findOrFail($companyId);

        $averageRating = Applications::whereHas('jobPosting', function ($query) use ($companyId) {
            $query->where('companies_id', $companyId);
        })
            ->whereNotNull('rating_company')
            ->where('rating_company', '>=', 1)
            ->where('rating_company', '<=', 5)
            ->avg('rating_company');

        $company->update([
            'avg_rating' => round($averageRating ?? 0, 2)
        ]);

        Log::info('Company average rating updated', [
            'company_id' => $companyId,
            'avg_rating' => round($averageRating ?? 0, 2)
        ]);
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $candidate = Candidates::where('user_id', $user->id)->first();

        if (!$candidate) {
            return response()->json(['error' => 'Candidate profile not found'], 404);
        }

        $type = $request->input('type', 'all');
        $statusFilter = $request->input('status');

        $data = [];

        switch ($type) {
            case 'reports':
                $query = Applications::where('candidates_id', $candidate->id)
                    ->where(function ($q) {
                        $q->whereNotNull('rating_company')
                            ->orWhereNotNull('review_company');
                    })
                    ->with(['jobPosting.company', 'jobPosting.typeJobs']);

                if ($statusFilter) {
                    $query->where('status', $statusFilter);
                }

                $data = $query->orderBy('updated_at', 'desc')->get();
                break;

            case 'feedback':
                $query = FeedbackApplication::whereHas('application', function ($q) use ($candidate, $statusFilter) {
                    $q->where('candidates_id', $candidate->id);
                    if ($statusFilter) {
                        $q->where('status', $statusFilter);
                    }
                })
                    ->where('given_by', 'company')
                    ->with(['application.jobPosting.company', 'application.jobPosting.typeJobs', 'feedback']);

                $data = $query->orderBy('created_at', 'desc')->get();
                break;

            case 'applications':
                $query = Applications::where('candidates_id', $candidate->id)
                    ->with([
                        'jobPosting.company',
                        'jobPosting.typeJobs',
                        'jobPosting.city'
                    ]);

                if ($statusFilter) {
                    $query->where('status', $statusFilter);
                }

                $data = $query->orderBy('updated_at', 'desc')->get();
                break;

            case 'invitations':
                $data = Applications::where('candidates_id', $candidate->id)
                    ->where('invited_by_company', 1)
                    ->with([
                        'jobPosting.company',
                        'jobPosting.typeJobs',
                        'jobPosting.city'
                    ])
                    ->orderBy('invited_at', 'desc')
                    ->get();
                break;

            case 'my_reports':
                $data = Reports::where('user_id', $user->id)
                    ->with([
                        'application.jobPosting.company',
                        'application.jobPosting.typeJobs',
                        'application.candidate'
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;

            default:
                $data = $this->buildAllItemsCollection($candidate, $statusFilter);
                break;
        }

        return response()->json($data);
    }

    private function getApplicationStatuses()
    {
        try {
            $type = DB::select("
                SELECT COLUMN_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = 'applications'
                AND COLUMN_NAME = 'status'
            ", [config('database.connections.mysql.database')]);

            if (empty($type)) {
                return $this->getDefaultStatuses();
            }

            $enumStr = $type[0]->COLUMN_TYPE;
            preg_match('/^enum\((.*)\)$/', $enumStr, $matches);

            if (!isset($matches[1])) {
                return $this->getDefaultStatuses();
            }

            $enumValues = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));

            return $enumValues;
        } catch (\Exception $e) {
            Log::error('Error getting application statuses: ' . $e->getMessage());
            return $this->getDefaultStatuses();
        }
    }

    public function getInvitations(Request $request)
    {
        try {
            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json(['error' => 'Candidate not found'], 404);
            }

            $invitations = Applications::where('candidates_id', $candidate->id)
                ->where('invited_by_company', 1)
                ->with([
                    'jobPosting.company',
                    'jobPosting.typeJobs',
                    'jobPosting.city',
                    'jobPosting.industry',
                    'jobPosting.skills',
                    'jobPosting.benefits.benefit',
                    'jobPosting.jobDatess.day'
                ])
                ->orderBy('invited_at', 'desc')
                ->paginate(10, ['*'], 'invitations_page');

            return response()->json($invitations);
        } catch (\Exception $e) {
            Log::error('Error fetching invitations: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch invitations'], 500);
        }
    }

    private function getDefaultStatuses()
    {
        return [
            'Invited',
            'Selection',
            'Pending',
            'Accepted',
            'Rejected',
            'Withdrawn',
            'Finished'
        ];
    }
    private function canUserGiveRating($applicationId, $userType)
    {
        // Cek apakah ada report yang approved untuk application ini
        $hasApprovedReport = Reports::where('application_id', $applicationId)
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedReport) {
            // Cek lebih detail - apakah yang di-approve adalah rating dari user type ini
            $application = Applications::find($applicationId);

            if ($userType === 'candidate') {
                // Jika rating_company sudah null DAN ada approved report, berarti sudah dihapus admin
                return is_null($application->rating_company);
            } else {
                // Jika rating_candidates sudah null DAN ada approved report, berarti sudah dihapus admin
                return is_null($application->rating_candidates);
            }
        }

        return true; // Boleh kasih rating
    }
}
