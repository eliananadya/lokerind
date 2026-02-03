<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reports;
use App\Models\Applications;
use App\Models\Candidates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportsUserController extends Controller
{
    /**
     * Report a company based on an application
     */
    public function reportCompany(Request $request)
    {
        try {
            Log::info('🚩 Report Company Request Received', [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            $request->validate([
                'application_id' => 'required|exists:applications,id',
                'reason' => 'required|string|min:10|max:500'
            ]);

            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                Log::warning('❌ Candidate not found', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan.'
                ], 404);
            }

            // Verify application belongs to this candidate
            $application = Applications::with('jobPosting.company')
                ->where('id', $request->application_id)
                ->where('candidates_id', $candidate->id)
                ->first();

            if (!$application) {
                Log::warning('❌ Application not found or not owned by candidate', [
                    'application_id' => $request->application_id,
                    'candidate_id' => $candidate->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Lamaran tidak ditemukan atau bukan milik Anda.'
                ], 404);
            }

            // Check if already reported
            $existingReport = Reports::where('user_id', $user->id)
                ->where('application_id', $request->application_id)
                ->first();

            if ($existingReport) {
                Log::info('⚠️ Duplicate report attempt', [
                    'user_id' => $user->id,
                    'application_id' => $request->application_id,
                    'existing_report_id' => $existingReport->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melaporkan perusahaan ini sebelumnya.'
                ], 422);
            }

            // Create the report
            $report = Reports::create([
                'user_id' => $user->id,
                'application_id' => $request->application_id,
                'reason' => $request->reason,
                'status' => 'pending'
            ]);

            Log::info('✅ Report created successfully', [
                'report_id' => $report->id,
                'user_id' => $user->id,
                'application_id' => $request->application_id,
                'company_id' => $application->jobPosting->company->id ?? null,
                'company_name' => $application->jobPosting->company->name ?? 'Unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan Anda telah diterima dan akan ditinjau oleh tim kami.',
                'report' => [
                    'id' => $report->id,
                    'status' => $report->status,
                    'created_at' => $report->created_at->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('⚠️ Validation failed for report', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('❌ Error creating report', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all reports by the authenticated user
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil kandidat tidak ditemukan.'
                ], 404);
            }

            $reports = Reports::where('user_id', $user->id)
                ->with(['application.jobPosting.company'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $reports
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reports', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat laporan.'
            ], 500);
        }
    }

    /**
     * Get a specific report
     */
    public function show($id)
    {
        try {
            $user = Auth::user();

            $report = Reports::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['application.jobPosting.company'])
                ->first();

            if (!$report) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $report
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching report', [
                'report_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail laporan.'
            ], 500);
        }
    }
}
