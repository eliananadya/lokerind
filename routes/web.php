<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthControllerUser;
use App\Http\Controllers\Company\CandidateRecommendationController;
use App\Http\Controllers\User\CandidateRiwayatControlller;
use App\Http\Controllers\Company\DashboardCompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Company\JobPostingController;
use App\Http\Controllers\Company\ProfileCompanyController;
use App\Http\Controllers\User\CompanyController;
use App\Http\Controllers\User\JobPostingUserController;
use App\Http\Controllers\User\HistoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\CandidatesUserController;
use App\Http\Controllers\User\ReportsUserController;
use App\Http\Controllers\User\SaveJobsUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Company\CandidateMatchController;
use App\Http\Controllers\Company\RiwayatCompanyController;

// ========== HOME ROUTE WITH REDIRECT LOGIC ==========
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role->name;

        return match ($role) {
            'super_admin' => redirect('/admin'),
            'company' => redirect()->route('company.dashboard'),
            'user' => app(HomeController::class)->index(),
            default => redirect()->route('login'),
        };
    }

    return app(HomeController::class)->index();
})->name('index.home');

// ========== PUBLIC ROUTES (Guest & User Only - Block Company/Admin) ==========
Route::group(['middleware' => function ($request, $next) {
    if (auth()->check()) {
        $role = auth()->user()->role->name;
        if (in_array($role, ['company', 'super_admin'])) {
            abort(403, 'Halaman ini tidak dapat diakses oleh perusahaan atau admin.');
        }
    }
    return $next($request);
}], function () {
    Route::get('/lowongan', [JobPostingUserController::class, 'index'])->name('jobs.index');
    Route::get('/search-jobs', [JobPostingUserController::class, 'searchJobs'])->name('search.jobs');
    Route::get('/jobs/{id}', [JobPostingUserController::class, 'show'])->name('jobs.show');
    Route::get('/perusahaan', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/search-companies', [CompanyController::class, 'searchCompanies'])->name('search.companies');
    Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
});

// ========== GUEST ROUTES (Login, Register, Password Reset) ==========
Route::middleware('guest')->group(function () {
    Route::post('/check-email', [RegisteredUserController::class, 'checkEmail'])->name('check.email');
    Route::post('/register-user', [RegisteredUserController::class, 'store'])->name('register.post');

    // Register
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Forgot Password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

// ========== USER/KANDIDAT ROUTES (Protected - Only User Role) ==========
Route::middleware(['auth', 'role:user'])->group(function () {
    // Job Actions
    Route::post('/save-job', [SaveJobsUserController::class, 'saveJob'])->name('save.job');
    Route::post('/unsave-job', [SaveJobsUserController::class, 'unsaveJob'])->name('unsave.job');
    Route::post('/apply-job', [JobPostingUserController::class, 'applyJob'])->name('apply.job');
    Route::post('/check-application', [JobPostingUserController::class, 'checkApplication'])->name('check.application');

    // API Routes for Job Actions
    Route::get('/api/jobs/{id}', [JobPostingUserController::class, 'show'])->name('api.jobs.show');
    Route::get('/api/check-application/{jobId}', [JobPostingUserController::class, 'checkApplication'])->name('api.check-application');
    Route::get('/api/check-saved/{jobId}', [JobPostingUserController::class, 'checkSaved'])->name('api.check-saved');
    Route::post('/api/jobs/{jobId}/save', [JobPostingUserController::class, 'toggleSave'])->name('api.jobs.save');
    Route::post('/api/jobs/{jobId}/apply', [JobPostingUserController::class, 'applyJob'])->name('api.jobs.apply');

    // Report & Subscribe Company
    Route::post('/report/company', [ReportsUserController::class, 'reportCompany'])->name('report.company');
    Route::post('/subscribe-company', [CompanyController::class, 'subscribeCompany'])->name('subscribe.company');
    Route::post('/unsubscribe-company', [CompanyController::class, 'unsubscribeCompany'])->name('unsubscribe.company');

    // Riwayat (History)
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/riwayat/filter', [HistoryController::class, 'filter'])->name('history.filter');
    Route::post('/applications/{id}/rate', [HistoryController::class, 'rate'])->name('applications.rate');
    Route::post('/applications/{id}/withdraw', [HistoryController::class, 'withdraw'])->name('applications.withdraw');
    Route::post('/applications/{id}/accept-invitation', [HistoryController::class, 'acceptInvitation'])->name('applications.accept');

    // Block/Unblock Company
    Route::post('/company/block', [CandidateRiwayatControlller::class, 'blockCompany'])->name('company.block');
    Route::post('/company/unblock', [CandidateRiwayatControlller::class, 'unblockCompany'])->name('company.unblock');

    // Activity
    Route::get('/activity', [CandidateRiwayatControlller::class, 'index'])->name('candidate.activity');
    Route::post('/activity/unsubscribe-company', [CandidateRiwayatControlller::class, 'unsubscribeCompany'])->name('activity.unsubscribe');
    Route::post('/save-job-history', [CandidateRiwayatControlller::class, 'saveJob'])->name('save.job-history');
    Route::post('/unsave-job-history', [CandidateRiwayatControlller::class, 'unsaveJob'])->name('unsave.job-history');

    // Profile & Candidate Management
    Route::get('/profile', [AuthControllerUser::class, 'index'])->name('profile.index');
    Route::post('/candidate/{candidate}/add-portfolio', [CandidatesUserController::class, 'addPortfolio'])->name('candidate.add.portfolio');
    Route::post('/candidate/{candidate}/update-skills', [CandidatesUserController::class, 'updateSkills'])->name('candidate.update.skills');
    Route::post('/candidate/skills', [CandidatesUserController::class, 'store'])->name('candidate.store');
    Route::post('/candidate/update-profile', [AuthControllerUser::class, 'updateProfile'])->name('candidate.updateProfile');
    Route::post('/candidate/update-photo', [AuthControllerUser::class, 'updatePhoto'])->name('candidate.updatePhoto');
    Route::delete('/candidate/{candidate}/delete-portfolio/{portfolio}', [CandidatesUserController::class, 'deletePortfolio'])->name('candidate.delete.portfolio');
});

// ========== EMAIL VERIFICATION & AUTH (All Authenticated Users) ==========
Route::middleware('auth')->group(function () {
    // Email Verification
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ========== COMPANY ROUTES (Protected - Only Company Role) ==========
Route::middleware(['auth', 'role:company'])->prefix('company')->name('company.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardCompanyController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardCompanyController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/interviewed', [DashboardCompanyController::class, 'getInterviewedApplicants'])->name('dashboard.interviewed');
    Route::get('/dashboard/not-interviewed', [DashboardCompanyController::class, 'getNotInterviewedApplicants'])->name('dashboard.not-interviewed');
    Route::get('/dashboard/jobs', [DashboardCompanyController::class, 'getAllJobs'])->name('dashboard.jobs');
    Route::get('/dashboard/invited', [DashboardCompanyController::class, 'getInvitedApplicants'])->name('dashboard.invited');
    Route::get('/dashboard/pending', [DashboardCompanyController::class, 'getPendingApplicants'])->name('dashboard.pending');
    Route::get('/dashboard/accepted', [DashboardCompanyController::class, 'getAcceptedApplicants'])->name('dashboard.accepted');
    Route::get('/dashboard/withdrawn', [DashboardCompanyController::class, 'getWithdrawnApplicants'])->name('dashboard.withdrawn');
    Route::get('/dashboard/rejected', [DashboardCompanyController::class, 'getRejectedApplicants'])->name('dashboard.rejected');
    Route::get('/dashboard/finished', [DashboardCompanyController::class, 'finished'])->name('dashboard.finished');
    Route::post('/dashboard/accept/{id}', [DashboardCompanyController::class, 'acceptApplicant'])->name('dashboard.accept');
    Route::post('/dashboard/reject/{id}', [DashboardCompanyController::class, 'rejectApplicant'])->name('dashboard.reject');

    // Applications Management
    Route::post('/applications/send-email', [DashboardCompanyController::class, 'sendEmail'])->name('applications.send-email');
    Route::get('/applications/{id}/detail', [DashboardCompanyController::class, 'getApplicationDetail'])->name('applications.detail');
    Route::post('/applications/{id}/update-status', [DashboardCompanyController::class, 'updateApplicationStatus'])->name('applications.update-status');

    // Job Posting Management
    Route::get('/jobs', [JobPostingController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [JobPostingController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobPostingController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}', [JobPostingController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{id}/edit', [JobPostingController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{id}', [JobPostingController::class, 'update'])->name('jobs.update');
    Route::get('/jobs/{id}/detail', [JobPostingController::class, 'getDetail'])->name('jobs.detail');
    Route::delete('/jobs/{id}', [JobPostingController::class, 'destroy'])->name('jobs.destroy');

    // Candidate Recommendations
    Route::get('/candidates/recommendations', [CandidateRecommendationController::class, 'index'])->name('candidates.recommendations');
    Route::get('/candidates/get-recommendations', [CandidateRecommendationController::class, 'getCandidates'])->name('candidates.get');
    Route::get('/candidates/job-skills/{jobId}', [CandidateRecommendationController::class, 'getJobSkills'])->name('candidates.job-skills');
    Route::get('/candidates/{id}/detail', [CandidateRecommendationController::class, 'getCandidateDetail'])->name('candidates.detail');
    Route::post('/candidates/send-invitation', [CandidateRecommendationController::class, 'sendInvitation'])->name('candidates.invite');

    // Candidate Match
    Route::get('/candidates/match', [CandidateMatchController::class, 'index'])->name('candidates.match');
    Route::get('/candidates/{candidate}/detail', [CandidateMatchController::class, 'getCandidateDetail'])->name('candidates.match.detail');
    Route::post('/candidates/{candidate}/invite', [CandidateMatchController::class, 'inviteCandidate'])->name('candidates.invite.action');
    Route::post('/candidates/invite/{candidate}', [CandidateMatchController::class, 'inviteCandidate'])->name('candidates.invite.post');

    // Riwayat
    Route::get('/riwayat', [RiwayatCompanyController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/filter', [RiwayatCompanyController::class, 'filter'])->name('riwayat.filter');
    Route::post('/riwayat/rate/{applicationId}', [RiwayatCompanyController::class, 'rateCandidate'])->name('riwayat.rate');
    Route::post('/riwayat/report/{application}', [RiwayatCompanyController::class, 'reportReview'])->name('riwayat.report');
    Route::post('/riwayat/block/{application}', [RiwayatCompanyController::class, 'blockUser'])->name('riwayat.block');

    // Profile
    Route::get('/profile', [ProfileCompanyController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileCompanyController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfileCompanyController::class, 'uploadPhoto'])->name('profile.uploadPhoto');
});
