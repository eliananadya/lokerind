<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Candidates;
use App\Models\SaveJobs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaveJobsUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function __construct()
    {
        $this->middleware('auth'); // Memastikan hanya yang login yang bisa mengakses
    }
    /**
     * Store a newly created resource in storage.
     */
    public function saveJob(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'job_id' => 'required|exists:job_postings,id',
            ]);

            // ✅ PERBAIKAN: Ambil candidate_id yang benar
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu.'
                ], 401);
            }

            // Ambil candidate berdasarkan user_id
            $candidate = Candidates::where('user_id', $user->id)->first();
            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan.  Lengkapi profil Anda terlebih dahulu.'
                ], 404);
            }

            // Cek apakah sudah disimpan
            $existingSave = SaveJobs::where('candidates_id', $candidate->id)
                ->where('job_posting_id', $request->job_id)
                ->first();

            if ($existingSave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pekerjaan sudah disimpan sebelumnya.'
                ], 400);
            }

            // Simpan job
            SaveJobs::create([
                'candidates_id' => $candidate->id,  // ✅ PAKAI candidate->id
                'job_posting_id' => $request->job_id,
            ]);

            Log::info('Job saved successfully', [
                'user_id' => $user->id,
                'candidate_id' => $candidate->id,
                'job_posting_id' => $request->job_posting_id

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pekerjaan berhasil disimpan.'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job ID tidak valid atau tidak ditemukan.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Save job error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pekerjaan: ' . $e->getMessage()
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
                    'message' => 'Profil kandidat tidak ditemukan.'
                ], 404);
            }

            $deleted = SaveJobs::where('candidates_id', $candidate->id)
                ->where('job_posting_id', $request->job_posting_id)
                ->delete();

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pekerjaan tidak ditemukan di daftar simpanan.'
                ], 404);
            }

            Log::info('Job unsaved successfully', [
                'user_id' => $user->id,
                'candidate_id' => $candidate->id,
                'job_id' => $request->job_id
            ]);

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
