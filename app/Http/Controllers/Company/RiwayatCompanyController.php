<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\Blacklist;
use App\Models\Companies;
use App\Models\Feedback;
use App\Models\FeedbackApplication;
use App\Models\JobPostings;
use App\Models\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RiwayatCompanyController extends Controller
{
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

            $statusFilter = $request->get('status');
            $searchFilter = $request->get('search');

            // Base query untuk semua tab
            $baseQuery = Applications::whereHas('jobPosting', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            });

            if ($statusFilter) {
                $baseQuery->where('status', $statusFilter);
            }
            if ($searchFilter) {
                $baseQuery->whereHas('jobPosting', function ($q) use ($searchFilter) {
                    $q->where('title', 'like', "%{$searchFilter}%");
                });
            }

            // ✅ TAMBAHKAN: Get reported application IDs oleh company ini
            $reportedApplicationIds = Reports::where('user_id', $user->id)
                ->pluck('application_id')
                ->toArray();

            // Tab 1: Applications - dengan withdraw_reason
            $applications = (clone $baseQuery)
                ->with([
                    'candidate.user',
                    'jobPosting.typeJobs',
                    'jobPosting.city',
                    'feedbackApplications' => function ($q) {
                        $q->with('feedback');
                    }
                ])
                ->select(['*'])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'apps_page');

            // Tab 2: Rating dan Review
            $ratingsFromCandidates = (clone $baseQuery)
                ->whereNotNull('rating_company')
                ->where('rating_company', '>=', 1)
                ->where('rating_company', '<=', 5)
                ->with([
                    'candidate.user',
                    'jobPosting.typeJobs',
                    'jobPosting.city'
                ])
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'ratings_page');

            // Tab 3: Reviews
            $reviewsFromCandidates = (clone $baseQuery)
                ->whereNotNull('review_company')
                ->where('review_company', '!=', '')
                ->with([
                    'candidate.user',
                    'jobPosting.typeJobs',
                    'jobPosting.city'
                ])
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'reviews_page');

            // Tab 4: Report
            $reviewsToReport = (clone $baseQuery)
                ->whereNotNull('review_company')
                ->where('review_company', '!=', '')
                ->with([
                    'candidate.user',
                    'jobPosting.typeJobs',
                    'jobPosting.city',
                    'reports' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'reviewsToReport_page');

            // Tab 5: My Reports
            $myReports = Reports::where('user_id', $user->id)
                ->with([
                    'application.candidate.user',
                    'application.jobPosting.typeJobs',
                    'application.jobPosting.city'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'myReports_page');

            // Tab 6: Blacklist
            $blacklistedCandidates = Blacklist::where('user_id', $user->id)
                ->with(['blockedUser.candidate'])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'blacklist_page');

            // Stats
            $stats = [
                'total_applications' => Applications::whereHas('jobPosting', fn($q) => $q->where('companies_id', $company->id))->count(),
                'pending_applications' => Applications::whereHas('jobPosting', fn($q) => $q->where('companies_id', $company->id))->where('status', 'Applied')->count(),
                'accepted_applications' => Applications::whereHas('jobPosting', fn($q) => $q->where('companies_id', $company->id))->where('status', 'Accepted')->count(),
                'rejected_applications' => Applications::whereHas('jobPosting', fn($q) => $q->where('companies_id', $company->id))->where('status', 'Rejected')->count(),
                'total_ratings' => Applications::whereHas('jobPosting', fn($q) => $q->where('companies_id', $company->id))->whereNotNull('rating_company')->where('rating_company', '>=', 1)->count(),
                'average_rating' => round($company->avg_rating ?? 0, 2),
                'total_reviews' => Applications::whereHas('jobPosting', fn($q) => $q->where('companies_id', $company->id))->whereNotNull('review_company')->where('review_company', '!=', '')->count(),
            ];

            $feedbacks = Feedback::where('for', 'candidate')
                ->where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();

            $blacklistedUsers = Blacklist::where('user_id', $user->id)
                ->pluck('blocked_user_id')
                ->toArray();

            return view('company.riwayat', compact(
                'applications',
                'ratingsFromCandidates',
                'reviewsFromCandidates',
                'reviewsToReport',
                'myReports',
                'blacklistedCandidates',
                'company',
                'stats',
                'feedbacks',
                'statusFilter',
                'searchFilter',
                'blacklistedUsers',
                'reportedApplicationIds' // ✅ TAMBAHKAN INI
            ));
        } catch (\Exception $e) {
            Log::error('Company History Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('company.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat halaman riwayat.');
        }
    }

    public function reportReview(Request $request, $applicationId)
    {
        try {
            $request->validate([
                'reason' => 'required|string|max:500',
            ]);

            $user = Auth::user();
            $application = Applications::findOrFail($applicationId);

            $existingReport = Reports::where('application_id', $applicationId)
                ->where('user_id', $user->id)
                ->first();

            if ($existingReport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melaporkan review ini sebelumnya.'
                ], 400);
            }

            Reports::create([
                'reason' => $request->reason,
                'status' => 'pending', // ✅ lowercase 'pending'
                'application_id' => $applicationId,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dikirim. Tim kami akan meninjau laporan Anda.'
            ]);
        } catch (\Exception $e) {
            Log::error('Report Review Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function blockUser(Request $request, $applicationId)
    {
        try {
            $request->validate([
                'reason' => 'required|string|max:500',
            ]);

            $user = Auth::user();
            $application = Applications::with('candidate.user')->findOrFail($applicationId);

            $blockedUserId = $application->candidate->user_id;

            // ✅ Cek apakah sudah diblokir
            $existingBlock = Blacklist::where('user_id', $user->id)
                ->where('blocked_user_id', $blockedUserId)
                ->first();

            if ($existingBlock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna ini sudah diblokir sebelumnya.'
                ], 400);
            }

            // ✅ Buat blacklist baru
            Blacklist::create([
                'reason' => $request->reason,
                'user_id' => $user->id,
                'blocked_user_id' => $blockedUserId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diblokir. Mereka tidak akan bisa melamar ke lowongan Anda lagi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Block User Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memblokir pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅✅✅ METHOD INI YANG DIUBAH - TAMBAHKAN CEK APPROVED REPORT ✅✅✅
    public function rateCandidate(Request $request, $applicationId)
    {
        // ✅ Validasi input
        $request->validate([
            'rating_candidates' => 'required|integer|min:1|max:5',
            'review_candidate' => 'nullable|string|max:1000',
            'feedbacks' => 'nullable|array',
            'feedbacks.*' => 'exists:feedback,id'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            // ✅ Cari application milik company ini
            $application = Applications::whereHas('jobPosting', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            })->findOrFail($applicationId);

            // ✅ VALIDASI: Hanya bisa rate kalau status = Finished
            if ($application->status !== 'Finished') {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating hanya bisa diberikan untuk aplikasi dengan status "Finished".'
                ], 422);
            }

            // ✅ CEK: Apakah rating company pernah dihapus admin karena dilaporkan candidate?
            $hasApprovedReportFromCandidate = Reports::where('application_id', $applicationId)
                ->where('status', 'approved')
                ->whereHas('user', function ($q) {
                    $q->where('role_id', 2); // Report DARI candidate (role_id = 2)
                })
                ->exists();

            // Jika candidate melaporkan (approved) dan rating_candidates sudah null, berarti dihapus admin
            if ($hasApprovedReportFromCandidate && is_null($application->rating_candidates)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating Anda telah dihapus oleh admin karena dilaporkan kandidat. Anda tidak dapat memberikan rating lagi untuk kandidat ini.'
                ], 403);
            }

            // ✅ VALIDASI: Cek apakah sudah pernah kasih rating
            if ($application->rating_candidates) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan rating untuk kandidat ini.'
                ], 422);
            }

            // ✅ Update rating dan review
            $application->update([
                'rating_candidates' => $request->rating_candidates,
                'review_candidate' => $request->review_candidate,
            ]);

            // ✅ Simpan feedback (jika ada)
            if ($request->has('feedbacks') && is_array($request->feedbacks)) {
                foreach ($request->feedbacks as $feedbackId) {
                    FeedbackApplication::create([
                        'given_by' => 'company',
                        'feedback_id' => $feedbackId,
                        'application_id' => $application->id,
                    ]);
                }
            }

            // ✅ Update average rating candidate
            $this->updateCandidateAverageRating($application->candidates_id);

            Log::info('Company rated candidate successfully', [
                'company_id' => $company->id,
                'application_id' => $application->id,
                'rating' => $request->rating_candidates,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rating dan review berhasil disimpan.',
                'data' => [
                    'rating' => $request->rating_candidates,
                    'review' => $request->review_candidate,
                    'feedbacks_count' => count($request->feedbacks ?? [])
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Application tidak ditemukan atau bukan milik company Anda.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rate candidate error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unblockUser(Request $request, $blacklistId)
    {
        try {
            $user = Auth::user();

            // ✅ Cari blacklist milik user ini
            $blacklist = Blacklist::where('id', $blacklistId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // ✅ Hapus dari blacklist
            $blacklist->delete();

            Log::info('User unblocked successfully', [
                'blacklist_id' => $blacklistId,
                'company_user_id' => $user->id,
                'unblocked_user_id' => $blacklist->blocked_user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil di-unblock. Mereka sekarang bisa melamar ke lowongan Anda.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data blacklist tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Unblock User Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal meng-unblock pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    private function updateCandidateAverageRating($candidateId)
    {
        $candidate = \App\Models\Candidates::findOrFail($candidateId);

        $averageRating = Applications::where('candidates_id', $candidateId)
            ->whereNotNull('rating_candidates')
            ->where('rating_candidates', '>=', 1)
            ->where('rating_candidates', '<=', 5)
            ->avg('rating_candidates');

        $candidate->update([
            'avg_rating' => round($averageRating ?? 0, 2)
        ]);

        Log::info('Candidate average rating updated', [
            'candidate_id' => $candidateId,
            'avg_rating' => round($averageRating ?? 0, 2)
        ]);
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $company = Companies::where('user_id', $user->id)->first();

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $type = $request->input('type', 'all');
        $statusFilter = $request->input('status');

        $data = $this->getFilteredData($company, $type, $statusFilter);

        return response()->json($data);
    }

    private function getFilteredData($company, $type, $statusFilter = null)
    {
        $baseQuery = Applications::whereHas('jobPosting', function ($query) use ($company) {
            $query->where('companies_id', $company->id);
        });

        if ($statusFilter) {
            $baseQuery->where('status', $statusFilter);
        }

        switch ($type) {
            case 'applications':
                return (clone $baseQuery)
                    ->with(['candidate.user', 'jobPosting.typeJobs', 'jobPosting.city'])
                    ->orderBy('created_at', 'desc')
                    ->get();

            case 'ratings':
                return (clone $baseQuery)
                    ->whereNotNull('rating_company')
                    ->with(['candidate.user', 'jobPosting.typeJobs'])
                    ->orderBy('updated_at', 'desc')
                    ->get();

            case 'reviews':
                return (clone $baseQuery)
                    ->whereNotNull('review_company')
                    ->where('review_company', '!=', '')
                    ->with(['candidate.user', 'jobPosting.typeJobs'])
                    ->orderBy('updated_at', 'desc')
                    ->get();

            case 'feedback':
                return (clone $baseQuery)
                    ->whereHas('feedbackApplications', function ($q) {
                        $q->where('given_by', 'company');
                    })
                    ->with([
                        'candidate.user',
                        'jobPosting.typeJobs',
                        'feedbackApplications' => function ($q) {
                            $q->where('given_by', 'company')->with('feedback');
                        }
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();

            default:
                return (clone $baseQuery)
                    ->with([
                        'candidate.user',
                        'jobPosting.typeJobs',
                        'jobPosting.city',
                        'feedbackApplications' => function ($q) {
                            $q->with('feedback');
                        }
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();
        }
    }
}
