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
                    'feedbackApplicationsFromCompany' => collect([]),
                    'feedbackApplicationsGivenByCandidate' => collect([]),
                    'ratingsReceived' => collect([]),
                    'allItems' => collect([]),
                    'feedbacks' => Feedback::all(),
                    'statusFilter' => null,
                    'reportedApplicationIds' => [],
                    'blockedCompanies' => collect([]),
                    'statusOptions' => $this->getDefaultStatuses()
                ])->with('info', 'Lengkapi profil Anda untuk melihat riwayat lengkap.');
            }

            // ✅ GET REPORTED APPLICATION IDS
            $reportedApplicationIds = Reports::where('user_id', $user->id)
                ->pluck('application_id')
                ->toArray();

            // ✅ GET BLOCKED COMPANIES
            $blockedCompanies = Blacklist::where('user_id', $user->id)
                ->with(['blockedUser.company'])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'blocked_page');

            $statusFilter = $request->get('status');

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

            $feedbackApplicationsFromCompany = FeedbackApplication::whereHas('application', function ($q) use ($candidate, $statusFilter) {
                $q->where('candidates_id', $candidate->id);
                if ($statusFilter) {
                    $q->where('status', $statusFilter);
                }
            })
                ->where('given_by', 'company')
                ->with([
                    'application.jobPosting.company',
                    'application.jobPosting.typeJobs',
                    'feedback'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'feedback_page');

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

            $allItemsCollection = $this->buildAllItemsCollection($candidate, $statusFilter);

            $perPage = 10;
            $currentPage = $request->get('all_page', 1);
            $allItems = new \Illuminate\Pagination\LengthAwarePaginator(
                $allItemsCollection->forPage($currentPage, $perPage),
                $allItemsCollection->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'pageName' => 'all_page']
            );

            $feedbacks = Feedback::all();
            $statusOptions = $this->getApplicationStatuses();

            return view('candidates.riwayat', compact(
                'reports',
                'applications',
                'feedbackApplicationsFromCompany',
                'feedbackApplicationsGivenByCandidate',
                'ratingsReceived',
                'allItems',
                'feedbacks',
                'statusOptions',
                'candidate',
                'reportedApplicationIds',
                'blockedCompanies',
                'statusFilter'
            ));
        } catch (\Exception $e) {
            Log::error('History page error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat halaman riwayat.');
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
                $q->whereNotNull('rating_candidates')
                    ->orWhereNotNull('review_candidate')
                    ->orWhereHas('feedbackApplications', function ($fq) {
                        $fq->where('given_by', 'company');
                    });
            });
        if ($statusFilter) {
            $reportsQuery->where('status', $statusFilter);
        }
        $allReports = $reportsQuery
            ->with([
                'jobPosting.company',
                'jobPosting.typeJobs',
                'jobPosting.city',
                'feedbackApplications' => function ($q) {
                    $q->where('given_by', 'company')->with('feedback');
                }
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

    public function withdraw(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'nullable|string|max:500'
            ]);

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
                ->whereIn('status', ['Applied', 'Reviewed', 'invited', 'Accepted'])
                ->firstOrFail();

            $oldStatus = $application->status;
            $oldPoint = $candidate->point ?? 0;
            $penaltyPoint = 5;
            $newPoint = max(0, $oldPoint - $penaltyPoint);

            if ($oldStatus === 'Accepted') {
                $jobPosting = $application->jobPosting;
                $jobPosting->increment('slot');

                Log::info('Job posting slot increased (application withdrawn)', [
                    'job_posting_id' => $jobPosting->id,
                    'remaining_slots' => $jobPosting->slot,
                    'application_id' => $id
                ]);
            }

            $application->update([
                'status' => 'Withdrawn',
                'withdrawn_at' => now(),
                'withdraw_reason' => $request->reason
            ]);

            $candidate->update(['point' => $newPoint]);

            HistoryPoint::create([
                'candidates_id' => $candidate->id,
                'application_id' => $application->id,
                'old_point' => $oldPoint,
                'new_point' => $newPoint,
                'reason' => 'withdraw_application'
            ]);

            Log::info('Application withdrawn', [
                'application_id' => $id,
                'candidate_id' => $candidate->id,
                'old_status' => $oldStatus,
                'old_point' => $oldPoint,
                'new_point' => $newPoint,
                'slot_returned' => $oldStatus === 'Accepted' ? 1 : 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lamaran berhasil ditarik. Poin Anda dikurangi ' . $penaltyPoint . ' poin.' . ($oldStatus === 'Accepted' ? ' Slot dikembalikan.' : ''),
                'data' => [
                    'old_point' => $oldPoint,
                    'new_point' => $newPoint,
                    'slot_returned' => $oldStatus === 'Accepted'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error withdrawing application', [
                'application_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menarik lamaran: ' . $e->getMessage()
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
                ->first();

            if (!$application) {
                Log::warning('Application not found', [
                    'application_id' => $id,
                    'candidate_id' => $candidate->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lamaran tidak ditemukan atau bukan milik Anda'
                ], 404);
            }

            if (!in_array($application->status, ['invited', 'Accepted'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status lamaran tidak valid untuk menerima undangan. Status saat ini: ' . $application->status
                ], 400);
            }

            $oldPoint = $candidate->point ?? 0;
            $bonusPoint = 10;
            $newPoint = min(100, $oldPoint + $bonusPoint);

            $application->update(['status' => 'Finished']);
            $candidate->update(['point' => $newPoint]);

            HistoryPoint::create([
                'candidates_id' => $candidate->id,
                'application_id' => $application->id,
                'old_point' => $oldPoint,
                'new_point' => $newPoint,
                'reason' => 'accept_invitation'
            ]);

            Log::info('Invitation accepted', [
                'application_id' => $id,
                'candidate_id' => $candidate->id,
                'old_status' => $application->status,
                'new_status' => 'Finished',
                'old_point' => $oldPoint,
                'new_point' => $newPoint
            ]);

            $actualReward = $newPoint - $oldPoint;
            $message = $newPoint >= 100
                ? 'Undangan berhasil diterima! Poin Anda sudah maksimal (100 poin)!'
                : "Undangan berhasil diterima! Anda mendapat +{$actualReward} poin!";

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'old_point' => $oldPoint,
                    'new_point' => $newPoint,
                    'status' => 'Finished',
                    'is_max' => $newPoint >= 100
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

            if ($application->status !== 'Accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya aplikasi dengan status Accepted yang bisa diberi rating.'
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

            $pointReward = 10;
            $oldPoint = $candidate->point ?? 0;
            $newPoint = min(100, $oldPoint + $pointReward);

            $candidate->update(['point' => $newPoint]);

            HistoryPoint::create([
                'candidates_id' => $candidate->id,
                'application_id' => $application->id,
                'old_point' => $oldPoint,
                'new_point' => $newPoint,
                'reason' => 'rate_company'
            ]);

            Log::info('Candidate rated company and received points', [
                'candidate_id' => $candidate->id,
                'application_id' => $application->id,
                'rating' => $request->rating_company,
                'point_reward' => $pointReward,
                'old_point' => $oldPoint,
                'new_point' => $newPoint
            ]);

            DB::commit();

            $actualReward = $newPoint - $oldPoint;
            $message = $newPoint >= 100
                ? "Terima kasih! Rating dan review Anda telah disimpan. Poin Anda sudah maksimal (100 poin)! 🎉"
                : "Terima kasih! Rating dan review Anda telah disimpan. Anda mendapat +{$actualReward} poin! 🎉";

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'old_point' => $oldPoint,
                    'new_point' => $newPoint,
                    'point_reward' => $actualReward,
                    'is_max' => $newPoint >= 100
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
            ->avg('rating_company');

        $company->update([
            'avg_rating' => round($averageRating, 2)
        ]);

        Log::info('Company average rating updated', [
            'company_id' => $companyId,
            'avg_rating' => round($averageRating, 2)
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
                        $q->whereNotNull('rating_candidates')
                            ->orWhereNotNull('review_candidate')
                            ->orWhereHas('feedbackApplications', function ($fq) {
                                $fq->where('given_by', 'company');
                            });
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

    private function getDefaultStatuses()
    {
        return [
            'invited',
            'Applied',
            'Reviewed',
            'Interview',
            'Accepted',
            'Rejected',
            'Withdrawn',
            'Finished'
        ];
    }
}
