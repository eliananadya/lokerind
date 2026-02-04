<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Applications;
use App\Models\Benefit;
use App\Models\Cities;
use App\Models\Companies;
use App\Models\Days;
use App\Models\Industries;
use App\Models\JobDates;
use App\Models\JobPostingBenefit;
use App\Models\JobPostings;
use App\Models\JobPostingSkills;
use App\Models\Skills;
use App\Models\TypeJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JobPostingController extends Controller
{
    // ✅ HELPER: Build dayMapping dari collection $days
    // Output: ['senin' => ['id' => 1, 'name' => 'Monday'], ...]
    private function buildDayMapping(array|object $days): array
    {
        $map = [
            'monday'    => 'senin',
            'tuesday'   => 'selasa',
            'wednesday' => 'rabu',
            'thursday'  => 'kamis',
            'friday'    => 'jumat',
            'saturday'  => 'sabtu',
            'sunday'    => 'minggu',
        ];

        $dayMapping = [];
        foreach ($days as $day) {
            $key = $map[strtolower($day->name)] ?? strtolower($day->name);
            $dayMapping[$key] = [
                'id'   => $day->id,
                'name' => $day->name,
            ];
        }

        return $dayMapping;
    }

    /**
     * Display a listing of job postings for the company
     */
    public function getDetail($id)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $job = JobPostings::with([
                'city',
                'industry',
                'typeJobs',
                'company',
                'skills',
                'applications'
            ])
                ->where('companies_id', $company->id)
                ->withCount('applications')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $job
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading job detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail lowongan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $company = Companies::where('user_id', $user->id)->first();

        if (!$company) {
            return redirect()->route('company.profile')->with('info', 'Lengkapi profil perusahaan terlebih dahulu.');
        }

        $query = JobPostings::where('companies_id', $company->id)
            ->with(['city', 'industry', 'typeJobs', 'applications']);

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Filter status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter verification
        if ($request->has('verification') && $request->verification) {
            $query->where('verification_status', $request->verification);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(10);

        // ✅ AJAX Response
        if ($request->ajax || $request->has('ajax')) {
            // Build HTML for table
            $html = view('company.jobs.index', compact('jobs', 'company'))->renderSections()['content'];

            // Extract only table part
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $tableWrapper = $dom->getElementById('jobsTableWrapper');
            $tableHtml = $dom->saveHTML($tableWrapper);

            return response()->json([
                'success' => true,
                'html' => $tableHtml,
                'stats' => [
                    'total' => $jobs->total(),
                    'active' => JobPostings::where('companies_id', $company->id)->where('status', 'Open')->count(),
                    'draft' => JobPostings::where('companies_id', $company->id)->where('status', 'Draft')->count(),
                    'applicants' => Applications::whereHas('jobPosting', function ($q) use ($company) {
                        $q->where('companies_id', $company->id);
                    })->count()
                ]
            ]);
        }

        return view('company.jobs.index', compact('jobs', 'company'));
    }

    /**
     * Show the form for creating a new job posting
     */
    public function create()
    {
        try {
            $user = Auth::user();

            $company = Companies::where('user_id', $user->id)->first();

            if (!$company) {
                Log::error('Company not found for user: ' . $user->id);
                return redirect()->route('company.dashboard')
                    ->with('error', 'Silakan lengkapi profil perusahaan terlebih dahulu.');
            }

            // Get all necessary data for the form
            $industries = Industries::all();
            $cities = Cities::all();
            $typeJobs = TypeJobs::all();
            $skills = Skills::all();
            $benefits = Benefit::all();
            $days = Days::all();

            // ✅ Build dayMapping untuk JS di view
            $dayMapping = $this->buildDayMapping($days);

            return view('company.jobs.create', compact(
                'company',
                'industries',
                'cities',
                'typeJobs',
                'skills',
                'benefits',
                'days',
                'dayMapping'
            ));
        } catch (\Exception $e) {
            Log::error('Job create form error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('company.jobs.index')
                ->with('error', 'Terjadi kesalahan saat memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created job posting in storage
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'salary' => 'required|integer|min:0',
                'type_salary' => 'required|in:total,shift',
                'address' => 'required|string|max:255',
                'min_age' => 'required|integer|min:17|max:100',
                'max_age' => 'required|integer|min:17|max:100|gte:min_age',
                'min_height' => 'required|integer|min:100|max:250',
                'min_weight' => 'required|integer|min:30|max:200',
                'gender' => 'required|in:Male,Female,All',
                'open_recruitment' => 'required|date',
                'close_recruitment' => 'required|date|after:open_recruitment',
                'slot' => 'required|integer|min:1',
                'level_english' => 'required|in:beginner,intermediate,expert',
                'level_mandarin' => 'required|in:beginner,intermediate,expert',
                'has_interview' => 'required|boolean',
                'industries_id' => 'required|exists:industries,id',
                'type_jobs_id' => 'required|exists:type_jobs,id',
                'cities_id' => 'required|exists:cities,id',
                'status' => 'required|in:Draft,Open',
                'skills' => 'nullable|array',
                'skills.*' => 'exists:skills,id',
                // Benefits validation
                'benefits' => 'nullable|array',
                'benefits.*.benefit_id' => 'nullable:benefits|exists:benefits,id',
                'benefits.*.benefit_type' => 'nullable|in:in kind,cash',
                'benefits.*.amount' => 'nullable|string|max:255',
                // Job dates validation
                'job_dates' => 'required|array|min:1',
                'job_dates.*.day_id' => 'required|exists:days,id',
                'job_dates.*.date' => 'required|date',
                'job_dates.*.start_time' => 'required|date_format:H:i',
                'job_dates.*.end_time' => 'required|date_format:H:i|after:job_dates.*.start_time',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            DB::beginTransaction();

            // Create job posting
            $jobPosting = JobPostings::create([
                'title' => $request->title,
                'description' => $request->description,
                'salary' => $request->salary,
                'type_salary' => $request->type_salary,
                'address' => $request->address,
                'min_age' => $request->min_age,
                'max_age' => $request->max_age,
                'min_height' => $request->min_height,
                'min_weight' => $request->min_weight,
                'verification_status' => 'Pending',
                'status' => $request->status,
                'gender' => $request->gender,
                'open_recruitment' => $request->open_recruitment,
                'close_recruitment' => $request->close_recruitment,
                'slot' => $request->slot,
                'level_english' => $request->level_english,
                'level_mandarin' => $request->level_mandarin,
                'has_interview' => $request->has_interview,
                'industries_id' => $request->industries_id,
                'companies_id' => $company->id,
                'type_jobs_id' => $request->type_jobs_id,
                'cities_id' => $request->cities_id,
            ]);

            // Attach skills
            if ($request->has('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skillId) {
                    JobPostingSkills::create([
                        'job_posting_id' => $jobPosting->id,
                        'skills_id' => $skillId,
                    ]);
                }
            }

            // Attach benefits with type and amount
            if ($request->has('benefits') && is_array($request->benefits)) {
                foreach ($request->benefits as $benefit) {
                    if (!empty($benefit['benefit_id'])) {
                        JobPostingBenefit::create([
                            'job_posting_id' => $jobPosting->id,
                            'benefit_id' => $benefit['benefit_id'],
                            'benefit_type' => $benefit['benefit_type'] ?? null,
                            'amount' => $benefit['amount'] ?? null,
                        ]);
                    }
                }
            }

            // Attach job dates with time
            if ($request->has('job_dates') && is_array($request->job_dates)) {
                foreach ($request->job_dates as $jobDate) {
                    JobDates::create([
                        'job_posting_id' => $jobPosting->id,
                        'days_id' => $jobDate['day_id'],
                        'date' => $jobDate['date'],
                        'start_time' => $jobDate['start_time'],
                        'end_time' => $jobDate['end_time'],
                    ]);
                }
            }

            DB::commit();

            Log::info('Job posting created successfully', [
                'job_id' => $jobPosting->id,
                'company_id' => $company->id,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->status === 'Open'
                    ? 'Lowongan berhasil dipublikasikan!'
                    : 'Lowongan berhasil disimpan sebagai draft!',
                'data' => $jobPosting
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ JOB POSTING CREATION ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan lowongan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified job posting
     */
    public function show($id)
    {
        try {
            Log::info('Accessing job show with ID: ' . $id);
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->first();
            if (!$company) {
                Log::error('Company not found for user: ' . $user->id);
                return redirect()->route('company.jobs.index')
                    ->with('error', 'Profil perusahaan tidak ditemukan.');
            }
            $job = JobPostings::find($id);
            if (!$job) {
                Log::error('Job posting not found with ID: ' . $id);
                return redirect()->route('company.jobs.index')
                    ->with('error', 'Lowongan pekerjaan tidak ditemukan.');
            }
            Log::info('Job found: ' . $job->id);
            return view('company.jobs.show', compact('job', 'company'));
        } catch (\Exception $e) {
            Log::error('Job show error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('company.jobs.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified job posting
     */
    public function edit($id)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $job = JobPostings::where('id', $id)
                ->where('companies_id', $company->id)
                ->with(['skills', 'benefits', 'days'])
                ->firstOrFail();

            // Get all necessary data for the form
            $industries = Industries::all();
            $cities = Cities::all();
            $typeJobs = TypeJobs::all();
            $skills = Skills::all();
            $benefits = Benefit::all();
            $days = Days::all();

            // ✅ Build dayMapping untuk JS di view
            $dayMapping = $this->buildDayMapping($days);

            return view('company.jobs.edit', compact(
                'job',
                'company',
                'industries',
                'cities',
                'typeJobs',
                'skills',
                'benefits',
                'days',
                'dayMapping'
            ));
        } catch (\Exception $e) {
            Log::error('Job edit form error: ' . $e->getMessage());
            return redirect()->route('company.jobs.index')->with('error', 'Lowongan tidak ditemukan.');
        }
    }

    public function updateApplicationStatus(Request $request, $applicationId)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            // Get application and verify it belongs to company's job
            $application = Applications::whereHas('job', function ($query) use ($company) {
                $query->where('companies_id', $company->id);
            })->findOrFail($applicationId);

            $validated = $request->validate([
                'status' => 'required|in:Accepted,Rejected'
            ]);

            $application->update([
                'status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => $validated['status'] == 'Accepted' ?
                    'Kandidat berhasil diterima!' :
                    'Kandidat berhasil ditolak.',
                'status' => $validated['status']
            ]);
        } catch (\Exception $e) {
            Log::error('Update application status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses.'
            ], 500);
        }
    }

    /**
     * Update the specified job posting in storage
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'salary' => 'required|integer|min:0',
                'type_salary' => 'required|in:total,shift',
                'address' => 'required|string|max:255',
                'min_age' => 'required|integer|min:17|max:100',
                'max_age' => 'required|integer|min:17|max:100|gte:min_age',
                'min_height' => 'required|integer|min:100|max:250',
                'min_weight' => 'required|integer|min:30|max:200',
                'gender' => 'required|in:Male,Female,All',
                'open_recruitment' => 'required|date',
                'close_recruitment' => 'required|date|after:open_recruitment',
                'slot' => 'required|integer|min:1',
                'level_english' => 'required|in:beginner,intermediate,expert',
                'level_mandarin' => 'required|in:beginner,intermediate,expert',
                'has_interview' => 'required|boolean',
                'industries_id' => 'required|exists:industries,id',
                'type_jobs_id' => 'required|exists:type_jobs,id',
                'cities_id' => 'required|exists:cities,id',
                'status' => 'required|in:Draft,Open,Closed',
                'skills' => 'nullable|array',
                'skills.*' => 'exists:skills,id',
                // Benefits validation
                'benefits' => 'nullable|array',
                'benefits.*.benefit_id' => 'required_with:benefits|exists:benefits,id',
                'benefits.*.benefit_type' => 'nullable|in:in kind,cash',
                'benefits.*.amount' => 'nullable|string|max:255',
                // Job dates validation
                'job_dates' => 'required|array|min:1',
                'job_dates.*.day_id' => 'required|exists:days,id',
                'job_dates.*.date' => 'required|date',
                'job_dates.*.start_time' => 'required|date_format:H:i',
                'job_dates.*.end_time' => 'required|date_format:H:i',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // ✅ CUSTOM VALIDATION: Check if end_time > start_time
            if ($request->has('job_dates') && is_array($request->job_dates)) {
                foreach ($request->job_dates as $index => $jobDate) {
                    if (isset($jobDate['start_time']) && isset($jobDate['end_time'])) {
                        $startTime = strtotime($jobDate['start_time']);
                        $endTime = strtotime($jobDate['end_time']);

                        if ($endTime <= $startTime) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Validasi gagal',
                                'errors' => [
                                    "job_dates.{$index}.end_time" => [
                                        "Jam selesai harus lebih besar dari jam mulai untuk jadwal ke-" . ($index + 1)
                                    ]
                                ]
                            ], 422);
                        }
                    }
                }
            }

            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $job = JobPostings::where('id', $id)
                ->where('companies_id', $company->id)
                ->firstOrFail();

            DB::beginTransaction();

            // Update job posting
            $job->update([
                'title' => $request->title,
                'description' => $request->description,
                'salary' => $request->salary,
                'type_salary' => $request->type_salary,
                'address' => $request->address,
                'min_age' => $request->min_age,
                'max_age' => $request->max_age,
                'min_height' => $request->min_height,
                'min_weight' => $request->min_weight,
                'gender' => $request->gender,
                'open_recruitment' => $request->open_recruitment,
                'close_recruitment' => $request->close_recruitment,
                'slot' => $request->slot,
                'level_english' => $request->level_english,
                'level_mandarin' => $request->level_mandarin,
                'has_interview' => $request->has_interview,
                'industries_id' => $request->industries_id,
                'type_jobs_id' => $request->type_jobs_id,
                'cities_id' => $request->cities_id,
                'status' => $request->status,
            ]);

            // Update skills
            JobPostingSkills::where('job_posting_id', $job->id)->delete();
            if ($request->has('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skillId) {
                    JobPostingSkills::create([
                        'job_posting_id' => $job->id,
                        'skills_id' => $skillId,
                    ]);
                }
            }

            // Update benefits with type and amount
            JobPostingBenefit::where('job_posting_id', $job->id)->delete();
            if ($request->has('benefits') && is_array($request->benefits)) {
                foreach ($request->benefits as $benefit) {
                    if (!empty($benefit['benefit_id'])) {
                        JobPostingBenefit::create([
                            'job_posting_id' => $job->id,
                            'benefit_id' => $benefit['benefit_id'],
                            'benefit_type' => $benefit['benefit_type'] ?? null,
                            'amount' => $benefit['amount'] ?? null,
                        ]);
                    }
                }
            }

            // Update job dates with time
            JobDates::where('job_posting_id', $job->id)->delete();
            if ($request->has('job_dates') && is_array($request->job_dates)) {
                foreach ($request->job_dates as $jobDate) {
                    JobDates::create([
                        'job_posting_id' => $job->id,
                        'days_id' => $jobDate['day_id'],
                        'date' => $jobDate['date'],
                        'start_time' => $jobDate['start_time'],
                        'end_time' => $jobDate['end_time'],
                    ]);
                }
            }

            DB::commit();

            Log::info('Job posting updated successfully', [
                'job_id' => $job->id,
                'company_id' => $company->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lowongan berhasil diperbarui!',
                'data' => $job
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ JOB POSTING UPDATE ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'job_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui lowongan',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified job posting from storage
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            $job = JobPostings::where('id', $id)
                ->where('companies_id', $company->id)
                ->firstOrFail();

            DB::beginTransaction();

            // Get job title before delete
            $jobTitle = $job->title;

            // Delete related records
            JobPostingSkills::where('job_posting_id', $job->id)->delete();
            JobPostingBenefit::where('job_posting_id', $job->id)->delete();
            JobDates::where('job_posting_id', $job->id)->delete();

            // Delete applications if any
            Applications::where('job_posting_id', $job->id)->delete();

            // Delete the job posting
            $job->delete();

            DB::commit();

            Log::info('Job posting deleted successfully', [
                'job_id' => $id,
                'job_title' => $jobTitle,
                'company_id' => $company->id
            ]);

            return redirect()->route('company.jobs.index')
                ->with('success', "Lowongan \"{$jobTitle}\" berhasil dihapus!");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ JOB DELETION ERROR', [
                'message' => $e->getMessage(),
                'job_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('company.jobs.index')
                ->with('error', 'Gagal menghapus lowongan. Silakan coba lagi.');
        }
    }
}
