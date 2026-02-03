<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\Blacklist;
use App\Models\Candidates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Subscribes;
use App\Models\SaveJobs;
use Illuminate\Support\Facades\Auth;

class CandidateRiwayatControlller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function unblockCompany(Request $request)
    {
        try {
            $validated = $request->validate([
                'blacklist_id' => 'required|exists:blacklists,id',
                'company_id' => 'required|exists:users,id'
            ]);

            $userId = Auth::id();
            $blacklistId = $validated['blacklist_id'];

            // Find and verify blacklist record belongs to current user
            $blacklist = Blacklist::where('id', $blacklistId)
                ->where('user_id', $userId)
                ->first();

            if (!$blacklist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record blokir tidak ditemukan atau tidak valid.'
                ], 404);
            }

            // Delete blacklist record
            $blacklist->delete();

            Log::info('Company unblocked successfully', [
                'user_id' => $userId,
                'blacklist_id' => $blacklistId,
                'unblocked_user_id' => $validated['company_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perusahaan berhasil dibuka blokirnya. Anda dapat melihat lowongan dari perusahaan ini lagi.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Unblock company error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuka blokir perusahaan.'
            ], 500);
        }
    }
    public function blockCompany(Request $request)
    {
        try {
            $validated = $request->validate([
                'blocked_user_id' => 'required|exists:users,id',
                'reason' => 'required|string|min:10|max:1000',
                'company_name' => 'nullable|string',
                'job_title' => 'nullable|string'
            ]);

            $userId = Auth::id();
            $blockedUserId = $validated['blocked_user_id'];

            // Check if already blocked
            $existingBlock = Blacklist::where('user_id', $userId)
                ->where('blocked_user_id', $blockedUserId)
                ->first();

            if ($existingBlock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perusahaan ini sudah diblokir sebelumnya.'
                ], 400);
            }

            // Verify blocked user exists and is a company
            $blockedUser = User::find($blockedUserId);
            if (!$blockedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            // Create blacklist record
            Blacklist::create([
                'user_id' => $userId,
                'blocked_user_id' => $blockedUserId,
                'reason' => $validated['reason']
            ]);

            Log::info('Company blocked successfully', [
                'user_id' => $userId,
                'blocked_user_id' => $blockedUserId,
                'company_name' => $request->company_name,
                'reason' => $validated['reason']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Perusahaan berhasil diblokir. Anda tidak akan melihat lowongan dari perusahaan ini lagi.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Block company error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memblokir perusahaan.'
            ], 500);
        }
    }
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            // ✅ Get candidate
            $candidate = Candidates::where('user_id', $user->id)->first();

            // ✅ Jika tidak ada candidate, tampilkan halaman dengan data kosong
            if (!$candidate) {
                return view('candidates.aktifitas', [
                    'subscribedCompanies' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, [
                        'path' => request()->url(),
                        'pageName' => 'subscribe_page',
                    ]),
                    'savedJobs' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, [
                        'path' => request()->url(),
                        'pageName' => 'saved_page',
                    ]),
                    'appliedJobIds' => [],
                    'candidate' => null,
                    'stats' => [
                        'total_subscriptions' => 0,
                        'total_saved_jobs' => 0,
                        'total_applications' => 0,
                        'active_applications' => 0,
                    ],
                ])->with('info', 'Lengkapi profil Anda untuk mulai menyimpan lowongan dan mengikuti perusahaan.');
            }

            // ✅ Get applied job IDs
            $appliedJobIds = Applications::where('candidates_id', $candidate->id)
                ->pluck('job_posting_id')
                ->toArray();

            // ✅ Get subscribed companies with pagination
            $subscribedCompanies = Subscribes::with([
                'company.industries',
                'company.jobPostings' => function ($query) {
                    $query->whereIn('status', ['Open', 'open', 'active', 'Active'])
                        ->with(['typeJobs', 'city', 'industry']) // ✅ Load relasi untuk job
                        ->orderBy('created_at', 'desc')
                        ->limit(5);
                }
            ])
                ->where('candidates_id', $candidate->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'subscribe_page');

            // ✅ Get saved jobs with pagination & relasi lengkap
            $savedJobs = SaveJobs::with([
                'jobPosting' => function ($query) {
                    $query->with([
                        'company.industries',
                        'typeJobs',
                        'city',
                        'industry',
                        'jobDatess.day', // ✅ Load job dates dengan relasi day
                        'benefits.benefit' // ✅ Load benefits jika diperlukan
                    ]);
                }
            ])
                ->where('candidates_id', $candidate->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'saved_page');

            // ✅ Get messages dari applications untuk saved jobs
            $savedJobIds = $savedJobs->pluck('job_posting_id')->toArray();

            $jobMessages = collect(); // Initialize empty collection

            if (!empty($savedJobIds)) {
                $jobMessages = Applications::where('candidates_id', $candidate->id)
                    ->whereIn('job_posting_id', $savedJobIds)
                    ->whereNotNull('message')
                    ->where('message', '!=', '') // Filter empty messages
                    ->get(['job_posting_id', 'message', 'status', 'updated_at'])
                    ->keyBy('job_posting_id');
            }

            // ✅ Transform saved jobs to add messages
            $savedJobs->getCollection()->transform(function ($item) use ($jobMessages) {
                // Attach message if exists
                if ($jobMessages->has($item->job_posting_id)) {
                    $message = $jobMessages->get($item->job_posting_id);
                    $item->application_message = $message->message;
                    $item->application_status = $message->status;
                    $item->application_date = $message->updated_at;
                } else {
                    // ✅ Set default values jika tidak ada message
                    $item->application_message = null;
                    $item->application_status = null;
                    $item->application_date = null;
                }

                return $item;
            });

            // ✅ Calculate statistics
            $stats = [
                'total_subscriptions' => Subscribes::where('candidates_id', $candidate->id)->count(),
                'total_saved_jobs' => SaveJobs::where('candidates_id', $candidate->id)->count(),
                'total_applications' => Applications::where('candidates_id', $candidate->id)->count(),
                'active_applications' => Applications::where('candidates_id', $candidate->id)
                    ->whereIn('status', ['pending', 'Applied', 'selection', 'interview'])
                    ->count(),
            ];

            return view('candidates.aktifitas', compact(
                'subscribedCompanies',
                'savedJobs',
                'appliedJobIds',
                'candidate',
                'stats'
            ));
        } catch (\Exception $e) {
            Log::error('=== ACTIVITY PAGE ERROR ===');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            // ✅ Return view dengan data kosong daripada redirect
            return view('candidates.aktifitas', [
                'subscribedCompanies' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, [
                    'path' => request()->url(),
                    'pageName' => 'subscribe_page',
                ]),
                'savedJobs' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, [
                    'path' => request()->url(),
                    'pageName' => 'saved_page',
                ]),
                'appliedJobIds' => [],
                'candidate' => null,
                'stats' => [
                    'total_subscriptions' => 0,
                    'total_saved_jobs' => 0,
                    'total_applications' => 0,
                    'active_applications' => 0,
                ],
            ])->with('error', 'Terjadi kesalahan saat memuat halaman aktivitas. Silakan coba lagi.');
        }
    }

    public function unsubscribeCompany(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $candidate = Auth::user()->candidate;

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => 'Kandidat tidak ditemukan'
            ], 404);
        }

        $deleted = Subscribes::where('candidates_id', $candidate->id)
            ->where('companies_id', $request->company_id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil berhenti mengikuti perusahaan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Subscription tidak ditemukan'
        ], 404);
    }
    public function saveJob(Request $request)
    {
        try {
            // ✅ PERBAIKAN: Validasi job_posting_id (sesuai JavaScript)
            $request->validate([
                'job_posting_id' => 'required|exists:job_postings,id',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu.'
                ], 401);
            }

            $candidate = Candidates::where('user_id', $user->id)->first();
            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan. Lengkapi profil Anda terlebih dahulu.'
                ], 404);
            }

            // ✅ Cek apakah sudah disimpan - gunakan job_posting_id
            $existingSave = SaveJobs::where('candidates_id', $candidate->id)
                ->where('job_posting_id', $request->job_posting_id)
                ->first();

            if ($existingSave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pekerjaan sudah disimpan sebelumnya.'
                ], 400);
            }

            // ✅ Simpan job - gunakan job_posting_id
            $savedJob = SaveJobs::create([
                'candidates_id' => $candidate->id,
                'job_posting_id' => $request->job_posting_id,
            ]);

            Log::info('✅ Job saved successfully', [
                'user_id' => $user->id,
                'candidate_id' => $candidate->id,
                'job_posting_id' => $request->job_posting_id,
                'saved_job_id' => $savedJob->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil disimpan ke favorit!'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('❌ Validation error in saveJob', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->errors()['job_posting_id'] ?? ['Job ID tidak ditemukan']),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('❌ Save job error', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pekerjaan. Silakan coba lagi.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function unsaveJob(Request $request)
    {
        try {
            $request->validate([
                'job_posting_id' => 'required|exists:job_postings,id',
            ]);

            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan.'
                ], 404);
            }

            $deleted = SaveJobs::where('candidates_id', $candidate->id)
                ->where('job_posting_id', $request->job_posting_id) // ✅ FIX DI SINI
                ->delete();

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pekerjaan tidak ditemukan di daftar simpanan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pekerjaan berhasil dihapus dari simpanan.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Unsave job error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus simpanan: ' . $e->getMessage()
            ], 500);
        }
    }

   //

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
