@extends('layouts.main')

{{-- Alert Notifications --}}
@if (session('info'))
    <div class="container mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if (!$candidate)
    <div class="container mt-3">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Profil Belum Lengkap!</strong>
            <a href="{{ route('profile.index') }}" class="alert-link">Lengkapi profil Anda</a>
            untuk mulai menyimpan lowongan dan mengikuti perusahaan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@section('content')
    <!-- Header Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--bg-blue) 0%, #e3f2fd 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-black fw-bold mb-2">Aktivitas Saya</h2>
                    <p class="text-black-50 mb-0">Kelola perusahaan yang Anda ikuti dan pekerjaan favorit Anda</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-3 justify-content-end">
                        <div class="text-black">
                            <h4 class="mb-0 fw-bold">{{ $subscribedCompanies->total() ?? 0 }}</h4>
                            <small>Subscribe</small>
                        </div>
                        <div class="text-black">
                            <h4 class="mb-0 fw-bold">{{ $savedJobs->total() ?? 0 }}</h4>
                            <small>Favorit</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activity Content -->
    <section class="py-5">
        <div class="container">
            <!-- Tab Navigation -->
            <ul class="nav nav-pills mb-4" id="activityTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4" id="subscribe-tab" data-bs-toggle="tab" data-bs-target="#subscribe"
                        type="button" role="tab">
                        <i class="bi bi-bell-fill me-2"></i>Perusahaan yang Diikuti
                        ({{ $subscribedCompanies->total() ?? 0 }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4" id="favorit-tab" data-bs-toggle="tab" data-bs-target="#favorit"
                        type="button" role="tab">
                        <i class="bi bi-bookmark-fill me-2"></i>Pekerjaan Favorit ({{ $savedJobs->total() ?? 0 }})
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="activityTabContent">
                <!-- Subscribe Tab -->
                <div class="tab-pane fade show active" id="subscribe" role="tabpanel" aria-labelledby="subscribe-tab">
                    @if ($subscribedCompanies->count() > 0)
                        <div class="row g-4" id="subscribed-companies-list">
                            @foreach ($subscribedCompanies as $subscribe)
                                @php
                                    $company = $subscribe->company;
                                    $openJobsCount = $company->jobPostings->where('status', 'Open')->count();
                                @endphp
                                <div class="col-lg-6" data-subscribe-id="{{ $subscribe->id }}">
                                    <div class="card company-activity-card h-100 border rounded-3 shadow-sm company-card"
                                        style="cursor: pointer;" data-company-id="{{ $company->id }}">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bold mb-2">{{ $company->name }}</h5>
                                                    <p class="text-muted mb-2">
                                                        <i class="bi bi-briefcase me-1"></i>
                                                        {{ $company->industries ? $company->industries->name : 'Industri tidak tersedia' }}
                                                    </p>
                                                    <p class="text-muted mb-2">
                                                        <i
                                                            class="bi bi-geo-alt me-1"></i>{{ $company->location ?? 'Lokasi tidak tersedia' }}
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar-check me-1"></i>
                                                        Diikuti sejak {{ $subscribe->created_at->format('d M Y') }}
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-primary px-3 py-2 mb-2">{{ $openJobsCount }}
                                                        Lowongan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $subscribedCompanies->appends(['saved_page' => request('saved_page')])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bell-slash text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Belum Ada Perusahaan yang Diikuti</h4>
                            <p class="text-muted">Mulai ikuti perusahaan untuk mendapatkan notifikasi lowongan terbaru</p>
                            <a href="{{ route('companies.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-search me-2"></i>Cari Perusahaan
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Favorit Tab -->
                <div class="tab-pane fade" id="favorit" role="tabpanel" aria-labelledby="favorit-tab">
                    @if ($savedJobs->count() > 0)
                        <div class="row g-4" id="saved-jobs-list">
                            @foreach ($savedJobs as $savedJob)
                                @php
                                    $job = $savedJob->jobPosting;
                                    $company = $job->company;
                                    $hasApplied = in_array($job->id, $appliedJobIds);
                                    $hasMessage = isset($savedJob->application_message);
                                @endphp
                                <div class="col-lg-6" data-saved-id="{{ $savedJob->id }}">
                                    <div class="card h-100 {{ $hasApplied ? 'applied-job' : '' }} border-2 shadow-sm job-card-clickable"
                                        style="border-color: var(--primary-blue) !important; border-radius: 12px; position: relative; cursor: pointer;"
                                        data-job-id="{{ $job->id }}"
                                        data-has-applied="{{ $hasApplied ? 'true' : 'false' }}">

                                        @if ($hasApplied)
                                            <div class="position-absolute end-0 top-0 m-3" style="z-index: 10;">
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Sudah Melamar
                                                </span>
                                            </div>
                                        @endif

                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="fw-bold mb-2">{{ $job->title }}</h5>
                                                    <p class="text-muted mb-0">{{ $company->name }}</p>
                                                </div>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($job->updated_at)->format('d M Y') }}</small>
                                            </div>

                                            <div class="mb-3">
                                                <span
                                                    class="badge bg-light text-dark me-2">{{ $job->typeJobs->name ?? 'N/A' }}</span>
                                                <span
                                                    class="badge bg-light text-dark">{{ $job->industry->name ?? 'N/A' }}</span>
                                            </div>

                                            <p class="text-muted mb-2">
                                                <i class="bi bi-geo-alt me-2"></i>{{ $job->city->name }}
                                            </p>

                                            @if ($hasMessage)
                                                <div class="alert alert-info mb-3 py-2" role="alert">
                                                    <i class="bi bi-envelope-fill me-2"></i>
                                                    <strong>Pesan dari Perusahaan:</strong>
                                                    <p class="mb-0 mt-1 small">{{ $savedJob->application_message }}</p>
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">
                                                        Gaji Rp {{ number_format($job->salary, 0, ',', '.') }}
                                                    </h4>
                                                    <div class="mb-2">
                                                        <span class="badge bg-primary" style="font-size: 0.75rem;">
                                                            <i class="bi bi-calendar-check me-1"></i>
                                                            {{ $job->type_salary == 'total' ? 'Total' : 'Per Hari' }}
                                                        </span>
                                                    </div>

                                                    {{-- Close Recruitment Date --}}
                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar-x me-1"></i>
                                                            <strong>Tutup:</strong>
                                                            @if ($job->close_recruitment)
                                                                @php
                                                                    $closeDate = \Carbon\Carbon::parse(
                                                                        $job->close_recruitment,
                                                                    );
                                                                    $now = \Carbon\Carbon::now();
                                                                    $daysLeft = $now->diffInDays($closeDate, false);
                                                                @endphp
                                                                <span
                                                                    class="{{ $daysLeft <= 3 && $daysLeft >= 0 ? 'text-danger fw-bold' : '' }}">
                                                                    {{ $closeDate->format('d M Y') }}
                                                                    @if ($daysLeft > 0)
                                                                        ({{ $daysLeft }} hari lagi)
                                                                    @elseif ($daysLeft == 0)
                                                                        <span class="badge bg-warning text-dark">Hari
                                                                            Terakhir!</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Sudah Ditutup</span>
                                                                    @endif
                                                                </span>
                                                            @else
                                                                <span class="text-muted">Belum ditentukan</span>
                                                            @endif
                                                        </small>
                                                    </div>

                                                    {{-- Job Dates Schedule --}}
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            <strong>Jadwal Kerja:</strong>
                                                        </small>
                                                        @if ($job->jobDatess->isNotEmpty())
                                                            @foreach ($job->jobDatess->take(3) as $jobDate)
                                                                <small class="text-muted d-block ms-3">
                                                                    <i class="bi bi-dot"></i>
                                                                    {{ \Carbon\Carbon::parse($jobDate->date)->format('d M Y') }}
                                                                    @if ($jobDate->day)
                                                                        <span class="badge bg-info text-white ms-1"
                                                                            style="font-size: 0.65rem;">
                                                                            {{ $jobDate->day->name }}
                                                                        </span>
                                                                    @endif
                                                                    @if ($jobDate->start_time && $jobDate->end_time)
                                                                        <span class="ms-1">
                                                                            <i class="bi bi-clock me-1"></i>
                                                                            {{ \Carbon\Carbon::parse($jobDate->start_time)->format('H:i') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($jobDate->end_time)->format('H:i') }}
                                                                        </span>
                                                                    @endif
                                                                </small>
                                                            @endforeach
                                                            @if ($job->jobDatess->count() > 3)
                                                                <small class="text-muted d-block ms-3 mt-1">
                                                                    <span class="badge bg-secondary"
                                                                        style="font-size: 0.65rem;">
                                                                        +{{ $job->jobDatess->count() - 3 }} jadwal lainnya
                                                                    </span>
                                                                </small>
                                                            @endif
                                                        @else
                                                            <small class="text-muted d-block ms-3">
                                                                <i class="bi bi-dot"></i>Tanggal belum ditentukan
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-column align-items-end gap-2 ms-3">
                                                    <small class="text-muted">Slot : {{ $job->slot }}</small>
                                                    <button type="button" class="btn-add-bookmark"
                                                        data-job-id="{{ $job->id }}"
                                                        onclick="event.stopPropagation();">
                                                        <i class="bi bi-bookmark-fill"
                                                            style="font-size: 1.5rem; color: #ffc107;"></i>
                                                    </button>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $savedJobs->appends(['subscribe_page' => request('subscribe_page')])->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bookmark text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Belum Ada Pekerjaan Favorit</h4>
                            <p class="text-muted">Simpan pekerjaan yang menarik untuk Anda lamar nanti</p>
                            <a href="{{ route('jobs.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-search me-2"></i>Cari Lowongan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Company Detail Modal -->
        <div class="modal fade" id="companyDetailModal" tabindex="-1" aria-labelledby="companyDetailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-5">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h3 class="fw-bold mb-2" id="modal-company-name">Nama perusahaan</h3>
                                <p class="text-muted mb-2" id="modal-company-industry">Tipe Industri</p>
                                <p class="text-muted mb-2" id="modal-company-location">
                                    <i class="bi bi-geo-alt me-1"></i>Lokasi Perusahaan
                                </p>
                                <p class="text-muted mb-0" id="modal-company-joindate">Join date</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="fw-bold fs-5" id="modal-company-rating">4.0</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted" id="modal-company-reviews">1 Ulasan</span>
                                </div>
                                <div class="mb-3">
                                    <span class="badge bg-secondary px-4 py-2" id="modal-company-jobs">20 Jobs</span>
                                </div>
                                <button class="btn btn-outline-secondary px-4" id="subscribe-btn">
                                    Subscribe
                                </button>
                            </div>
                        </div>

                        <ul class="nav nav-tabs border-bottom" id="companyTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="informasi-tab" data-bs-toggle="tab"
                                    data-bs-target="#informasi" type="button" role="tab">
                                    Informasi Perusahaan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="lowongan-tab" data-bs-toggle="tab"
                                    data-bs-target="#lowongan" type="button" role="tab">
                                    Lowongan Pekerjaan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rating-tab" data-bs-toggle="tab" data-bs-target="#rating"
                                    type="button" role="tab">
                                    Rating dan Ulasan
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-4" id="companyTabContent">
                            <div class="tab-pane fade show active" id="informasi" role="tabpanel">
                                <div class="bg-light rounded p-4">
                                    <h5 class="fw-bold mb-3">Deskripsi</h5>
                                    <p class="text-muted" id="company-description">Loading...</p>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="lowongan" role="tabpanel">
                                <div id="company-jobs" class="row g-3"></div>
                                <div class="d-flex justify-content-center mt-4" id="jobs-pagination-container"></div>
                            </div>

                            <div class="tab-pane fade" id="rating" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fw-bold mb-4">Rating Breakdown</h5>
                                        <div id="rating-breakdown"></div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <div class="bg-light mb-4 rounded p-4">
                                            <h1 class="display-3 fw-bold mb-0" id="avg-rating-display">4.0</h1>
                                            <div class="mb-2" id="star-rating-display">
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <i class="bi bi-star text-warning"></i>
                                            </div>
                                            <p class="text-muted" id="total-ratings">20 ratings</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div id="reviews-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Detail Modal -->
        <div class="modal fade" id="jobDetailModal" tabindex="-1" aria-labelledby="jobDetailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="jobDetailModalLabel">Job Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="card h-100" id="job-info-card" data-job-id="">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="fw-bold mb-2" id="job-title"></h5>
                                            <p class="text-muted mb-0" id="company-name"></p>
                                        </div>
                                        <small class="text-muted" id="updated-at"></small>
                                    </div>

                                    <div class="mb-3" id="job-type-industry"></div>
                                    <p class="text-muted mb-2" id="job-location"></p>
                                    <div id="salary-slot"></div>

                                    <div class="text-end mt-3">
                                        <div class="d-flex flex-column align-items-end gap-2">
                                            <button class="btn btn-primary w-100" id="apply-btn"
                                                style="max-width: 200px;">
                                                <i class="bi bi-send me-1"></i><span class="apply-text">Apply</span>
                                            </button>
                                            <button class="btn btn-outline-warning w-100" id="save-btn-modal"
                                                style="max-width: 200px;">
                                                <i class="bi bi-bookmark me-1"></i><span class="save-text">Simpan</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center mt-4 text-center">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="informasi-lowongan-tab" data-bs-toggle="tab"
                                            href="#informasi-lowongan" role="tab">Informasi Lowongan</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="kualifikasi-tab" data-bs-toggle="tab"
                                            href="#kualifikasi" role="tab">Kualifikasi</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="benefit-tab" data-bs-toggle="tab" href="#benefit"
                                            role="tab">Benefit</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="informasi-lowongan" role="tabpanel">
                                    <div id="informasi-lowongan-content"></div>
                                </div>
                                <div class="tab-pane fade" id="kualifikasi" role="tabpanel">
                                    <div id="kualifikasi-content"></div>
                                </div>
                                <div class="tab-pane fade" id="benefit" role="tabpanel">
                                    <div id="benefit-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .nav-pills {
            --bs-nav-pills-link-active-bg: transparent;
        }

        .activity-card {
            transition: all 0.3s ease;
        }

        .activity-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-outline-primary:hover {
            background-color: var(--bg-blue);
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .border-info {
            border-color: #0dcaf0 !important;
        }

        .alert-info {
            background-color: #cff4fc;
            border-color: #b6effb;
        }

        .company-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .company-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .job-card-clickable {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .job-card-clickable:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .btn-add-bookmark {
            position: relative;
            z-index: 100 !important;
            cursor: pointer !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            transition: transform 0.2s ease;
        }

        .btn-add-bookmark:hover {
            transform: scale(1.2);
        }

        .btn-add-bookmark i {
            pointer-events: none;
        }

        .applied-job {
            position: relative;
            opacity: 0.95;
        }

        .applied-job .card-body {
            position: relative;
            z-index: 1;
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
                console.log('✅ Activity page script loaded');

                // ========== GLOBAL VARIABLES ==========
                let currentCompanyData = null;
                let currentCompanyId = null;
                let currentJobId = null;
                let isSubscribed = false;
                let currentJobsPage = 1;
                let jobsPerPage = 10;
                let allOpenJobs = [];
                let savedJobIdsGlobal = @json($savedJobIds ?? []);
                let appliedJobIdsGlobal = @json($appliedJobIds ?? []);
                let subscribedCompanyIdsGlobal = @json($subscribedCompanyIds ?? []);

                console.log('Initial Saved Jobs:', savedJobIdsGlobal);
                console.log('Initial Applied Jobs:', appliedJobIdsGlobal);
                console.log('Initial Subscribed Companies:', subscribedCompanyIdsGlobal);

                // ========== HELPER FUNCTIONS ==========
                function formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num || 0);
                }

                function formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                }

                function showToast(icon, title, text) {
                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: text,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }

                // ========== COMPANY CARD CLICK ==========
                $(document).on('click', '.company-card', function(e) {
                    if ($(e.target).closest('.unsubscribe-btn').length) return;

                    const companyId = $(this).data('company-id');
                    console.log('🟢 Company card clicked, ID:', companyId);
                    currentCompanyId = companyId;
                    loadCompanyDetails(companyId);
                });

                // ========== LOAD COMPANY DETAILS ==========
                function loadCompanyDetails(companyId) {
                    console.log('🔄 Loading company details for ID:', companyId);

                    $.ajax({
                        url: '/companies/' + companyId,
                        method: 'GET',
                        dataType: 'json',
                        cache: false,
                        success: function(data) {
                            console.log('✅ Company data received:', data);
                            currentCompanyData = data;
                            renderCompanyDetails(data);
                            $('#companyDetailModal').modal('show');
                        },
                        error: function(xhr) {
                            console.error('❌ Error loading company:', xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal memuat data perusahaan'
                            });
                        }
                    });
                }

                // ========== RENDER COMPANY DETAILS ==========
                function renderCompanyDetails(data) {
                    const company = data.company;
                    isSubscribed = data.isSubscribed || subscribedCompanyIdsGlobal.includes(company.id);

                    $('#modal-company-name').text(company.name || 'Nama perusahaan');
                    $('#modal-company-industry').text(company.industries?.name || 'Tipe Industri');
                    $('#modal-company-location').html('<i class="bi bi-geo-alt me-1"></i>' + (company.location ||
                        'Lokasi tidak tersedia'));

                    const joinDate = company.created_at ? new Date(company.created_at).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) : 'Tanggal tidak tersedia';
                    $('#modal-company-joindate').text('Join date: ' + joinDate);

                    const rating = parseFloat(company.avg_rating) || 0;
                    $('#modal-company-rating').text(rating.toFixed(1));
                    $('#avg-rating-display').text(rating.toFixed(1));

                    const totalReviews = data.total_reviews || 0;
                    $('#modal-company-reviews').text(totalReviews + ' Ulasan');
                    $('#total-ratings').text(totalReviews + ' ratings');

                    let stars = '';
                    for (let i = 1; i <= 5; i++) {
                        stars += i <= Math.floor(rating) ?
                            '<i class="bi bi-star-fill text-warning"></i>' :
                            '<i class="bi bi-star text-warning"></i>';
                    }
                    $('#star-rating-display').html(stars);

                    const openJobsCount = company.job_postings ? company.job_postings.filter(job => job
                        .status === 'Open').length : 0;
                    $('#modal-company-jobs').text(openJobsCount + ' Jobs');

                    $('#company-description').text(company.description || 'Deskripsi tidak tersedia');

                    updateSubscribeButton(isSubscribed);
                    renderJobListings(company.job_postings, data.savedJobIds || savedJobIdsGlobal);
                    renderReviews(company.reviews, data.rating_stats);
                }

                // ========== RENDER JOB LISTINGS ==========
                function renderJobListings(jobs, savedJobIds) {
                    const container = $('#company-jobs');
                    container.empty();

                    if (!jobs || jobs.length === 0) {
                        container.html(
                            '<div class="col-12 text-center py-4"><i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i><p class="text-muted mt-2">Belum ada lowongan</p></div>'
                        );
                        $('#jobs-pagination-container').empty();
                        return;
                    }

                    allOpenJobs = jobs.filter(job => job.status === 'Open');

                    if (allOpenJobs.length === 0) {
                        container.html(
                            '<div class="col-12 text-center py-4"><p class="text-muted">Tidak ada lowongan yang sedang dibuka</p></div>'
                        );
                        $('#jobs-pagination-container').empty();
                        return;
                    }

                    const totalPages = Math.ceil(allOpenJobs.length / jobsPerPage);
                    const startIndex = (currentJobsPage - 1) * jobsPerPage;
                    const endIndex = startIndex + jobsPerPage;
                    const jobsToDisplay = allOpenJobs.slice(startIndex, endIndex);

                    jobsToDisplay.forEach(function(job) {
                        const isSaved = savedJobIds.includes(job.id);
                        const salaryType = job.type_salary === 'total' ? 'Total' : 'Per Hari';
                        const createdDate = job.created_at ? new Date(job.created_at).toLocaleDateString(
                            'id-ID', {
                                day: '2-digit',
                                month: 'long'
                            }) : '';

                        // Close Recruitment
                        let closeRecruitmentHTML = '';
                        if (job.close_recruitment) {
                            const closeDate = new Date(job.close_recruitment);
                            const now = new Date();
                            const diffTime = closeDate - now;
                            const daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            const formattedDate = closeDate.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });

                            let statusHTML = '';
                            let textClass = '';

                            if (daysLeft > 3) {
                                statusHTML = `(${daysLeft} hari lagi)`;
                            } else if (daysLeft > 0) {
                                statusHTML =
                                    `<span class="badge bg-warning text-dark ms-1">${daysLeft} hari lagi!</span>`;
                                textClass = 'text-danger fw-bold';
                            } else if (daysLeft === 0) {
                                statusHTML =
                                    `<span class="badge bg-warning text-dark ms-1">Hari Terakhir!</span>`;
                                textClass = 'text-danger fw-bold';
                            } else {
                                statusHTML = `<span class="badge bg-danger ms-1">Sudah Ditutup</span>`;
                                textClass = 'text-muted';
                            }

                            closeRecruitmentHTML = `
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-calendar-x me-1"></i>
                                    <strong>Tutup:</strong>
                                    <span class="${textClass}">
                                        ${formattedDate} ${statusHTML}
                                    </span>
                                </small>
                            `;
                        } else {
                            closeRecruitmentHTML = `
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-calendar-x me-1"></i>
                                    <strong>Tutup:</strong> Belum ditentukan
                                </small>
                            `;
                        }

                        // Work Schedule
                        let workScheduleHTML = '';
                        if (job.job_datess && job.job_datess.length > 0) {
                            workScheduleHTML =
                                `<div class="mb-2"><small class="text-muted d-block mb-1"><i class="bi bi-calendar-event me-1"></i><strong>Jadwal Kerja:</strong></small>`;

                            const maxDisplay = Math.min(3, job.job_datess.length);
                            for (let i = 0; i < maxDisplay; i++) {
                                const scheduleItem = job.job_datess[i];
                                const scheduleDateFormatted = new Date(scheduleItem.date).toLocaleDateString(
                                    'id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    });
                                const dayName = scheduleItem.day ? scheduleItem.day.name : '';
                                const startTime = scheduleItem.start_time ? scheduleItem.start_time.substring(0,
                                    5) : '';
                                const endTime = scheduleItem.end_time ? scheduleItem.end_time.substring(0, 5) :
                                    '';

                                workScheduleHTML += `
                                    <small class="text-muted d-block ms-3">
                                        <i class="bi bi-dot"></i>
                                        ${scheduleDateFormatted}
                                        ${dayName ? `<span class="badge bg-info text-white ms-1" style="font-size: 0.65rem;">${dayName}</span>` : ''}
                                        ${startTime && endTime ? `<span class="ms-1"><i class="bi bi-clock me-1"></i>${startTime} - ${endTime}</span>` : ''}
                                    </small>
                                `;
                            }

                            if (job.job_datess.length > 3) {
                                workScheduleHTML += `
                                    <small class="text-muted d-block ms-3 mt-1">
                                        <span class="badge bg-secondary" style="font-size: 0.65rem;">
                                            +${job.job_datess.length - 3} jadwal lainnya
                                        </span>
                                    </small>
                                `;
                            }

                            workScheduleHTML += '</div>';
                        } else {
                            workScheduleHTML = `
                                <small class="text-muted d-block">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Tanggal belum ditentukan
                                </small>
                            `;
                        }

                        container.append(`
                            <div class="col-md-6">
                                <div class="card h-100 border rounded-3 shadow-sm hover-card job-card-clickable"
                                    style="cursor: pointer;"
                                    data-job-id="${job.id}">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-2">${job.title || 'Judul Pekerjaan'}</h6>
                                                <p class="text-muted mb-0">${currentCompanyData.company.name}</p>
                                            </div>
                                            <small class="text-muted text-nowrap ms-2">${createdDate}</small>
                                        </div>

                                        <div class="mb-3">
                                            <span class="badge bg-light text-dark border me-1">${job.type_jobs ? job.type_jobs.name : 'Tipe Pekerjaan'}</span>
                                            <span class="badge bg-light text-dark border">${job.industry ? job.industry.name : 'Tipe Industri'}</span>
                                        </div>

                                        <p class="text-muted mb-2">
                                            <i class="bi bi-geo-alt me-1"></i>${job.city ? job.city.name : 'Lokasi Pekerjaan'}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-1" style="color: var(--primary-blue);">
                                                    Rp ${formatNumber(job.salary || 0)}
                                                </h5>
                                                <div class="mb-2">
                                                    <span class="badge bg-primary" style="font-size: 0.7rem;">
                                                        <i class="bi bi-calendar-check me-1"></i>${salaryType}
                                                    </span>
                                                </div>

                                                ${closeRecruitmentHTML}
                                                ${workScheduleHTML}
                                            </div>

                                            <div class="text-end ms-3">
                                                <small class="text-muted d-block mb-2">
                                                    <strong>Slot:</strong> ${job.slot || 0}
                                                </small>
                                                <button class="btn btn-link p-0 text-muted bookmark-job-btn"
                                                    data-job-id="${job.id}"
                                                    onclick="event.stopPropagation();">
                                                    <i class="bi ${isSaved ? 'bi-bookmark-fill' : 'bi-bookmark'}"
                                                        style="font-size: 1.3rem; color: ${isSaved ? '#ffc107' : '#6c757d'}"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    if (totalPages > 1) {
                        updateJobsPagination(currentJobsPage, totalPages);
                    } else {
                        $('#jobs-pagination-container').empty();
                    }

                    console.log('Jobs rendered on page:', currentJobsPage);
                }

                // ========== RENDER REVIEWS ==========
                function renderReviews(reviews, stats) {
                    const container = $('#reviews-list');
                    container.empty();

                    if (!reviews || reviews.length === 0) {
                        container.html(
                            '<div class="text-center py-4"><i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i><p class="text-muted mt-2">Belum ada review</p></div>'
                        );
                        $('#rating-breakdown').html('<p class="text-muted">Belum ada rating</p>');
                        return;
                    }

                    // Rating Breakdown
                    let statsHTML = '<div class="mb-4">';
                    for (let i = 5; i >= 1; i--) {
                        const count = stats[i] || 0;
                        const percentage = reviews.length > 0 ? (count / reviews.length * 100) : 0;
                        statsHTML += `
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2" style="width: 60px;">${i} <i class="bi bi-star-fill text-warning"></i></span>
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: ${percentage}%"></div>
                                </div>
                                <span class="ms-2 text-muted">${count}</span>
                            </div>
                        `;
                    }
                    statsHTML += '</div>';
                    $('#rating-breakdown').html(statsHTML);

                    // Reviews List
                    reviews.slice(0, 5).forEach(review => {
                        const stars = '★'.repeat(review.rating_company) + '☆'.repeat(5 - review
                            .rating_company);
                        const reviewDate = review.created_at ? formatDate(review.created_at) : 'N/A';
                        container.append(`
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">${review.candidate?.name || 'Anonymous'}</h6>
                                            <div class="text-warning mb-1">${stars}</div>
                                        </div>
                                        <small class="text-muted">${reviewDate}</small>
                                    </div>
                                    <p class="text-muted mb-0">${review.review_company || 'Tidak ada komentar'}</p>
                                </div>
                            </div>
                        `);
                    });

                    if (reviews.length > 5) {
                        container.append(
                            `<p class="text-center text-muted">Dan ${reviews.length - 5} review lainnya...</p>`);
                    }
                }

                // ========== UPDATE SUBSCRIBE BUTTON ==========
                function updateSubscribeButton(subscribed) {
                    const $btn = $('#subscribe-btn');
                    if (subscribed) {
                        $btn.removeClass('btn-outline-secondary').addClass('btn-danger')
                            .html('<i class="bi bi-x-circle me-2"></i>Unsubscribe')
                            .prop('disabled', false);
                    } else {
                        $btn.removeClass('btn-danger').addClass('btn-outline-secondary')
                            .html('<i class="bi bi-bell me-2"></i>Subscribe')
                            .prop('disabled', false);
                    }
                }

                // ========== SUBSCRIBE BUTTON CLICK ==========
                $('#subscribe-btn').on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        @guest
                        Swal.fire({
                            icon: 'warning',
                            title: 'Belum Login',
                            text: 'Anda harus login terlebih dahulu untuk subscribe perusahaan.',
                            showCancelButton: true,
                            confirmButtonText: 'Login Sekarang',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#14489b',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('login') }}';
                            }
                        });
                        return;
                    @endguest

                    if (!currentCompanyId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Company ID tidak ditemukan!'
                        });
                        return;
                    }

                    const companyName = $('#modal-company-name').text();

                    if (isSubscribed) {
                        Swal.fire({
                            title: 'Unsubscribe Perusahaan?',
                            text: `Apakah Anda yakin ingin berhenti mengikuti "${companyName}"?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Unsubscribe',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#dc3545',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                performUnsubscribe(currentCompanyId);
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Subscribe Perusahaan?',
                            text: `Apakah Anda ingin mengikuti "${companyName}"?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Subscribe',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#28a745',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                performSubscribe(currentCompanyId);
                            }
                        });
                    }
                });

            function performSubscribe(companyId) {
                const $btn = $('#subscribe-btn');
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

                $.ajax({
                    url: '{{ route('subscribe.company') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        company_id: companyId
                    },
                    success: function(response) {
                        if (response.success) {
                            isSubscribed = true;
                            if (!subscribedCompanyIdsGlobal.includes(companyId)) {
                                subscribedCompanyIdsGlobal.push(companyId);
                            }
                            updateSubscribeButton(true);
                            showToast('success', 'Berhasil!', response.message);
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat subscribe.'
                        });
                        updateSubscribeButton(isSubscribed);
                    }
                });
            }

            function performUnsubscribe(companyId) {
                const $btn = $('#subscribe-btn');
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

                $.ajax({
                    url: '{{ route('unsubscribe.company') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        company_id: companyId
                    },
                    success: function(response) {
                        if (response.success) {
                            isSubscribed = false;
                            subscribedCompanyIdsGlobal = subscribedCompanyIdsGlobal.filter(id => id !==
                                companyId);
                            updateSubscribeButton(false);
                            showToast('success', 'Berhasil!', response.message);
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat unsubscribe.'
                        });
                        updateSubscribeButton(isSubscribed);
                    }
                });
            }

            // ========== JOBS PAGINATION ==========
            function updateJobsPagination(currentPage, totalPages) {
                const container = $('#jobs-pagination-container');
                container.empty();

                if (totalPages <= 1) return;

                let html = '<nav><ul class="pagination justify-content-center">';

                if (currentPage > 1) {
                    html +=
                        `<li class="page-item"><a class="page-link jobs-pagination-link" href="#" data-page="${currentPage - 1}">&laquo;</a></li>`;
                } else {
                    html += '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
                }

                for (let i = 1; i <= totalPages; i++) {
                    if (i === currentPage) {
                        html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                    } else if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                        html +=
                            `<li class="page-item"><a class="page-link jobs-pagination-link" href="#" data-page="${i}">${i}</a></li>`;
                    } else if (i === currentPage - 3 || i === currentPage + 3) {
                        html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }

                if (currentPage < totalPages) {
                    html +=
                        `<li class="page-item"><a class="page-link jobs-pagination-link" href="#" data-page="${currentPage + 1}">&raquo;</a></li>`;
                } else {
                    html += '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
                }

                html += '</ul></nav>';
                container.html(html);
            }

            $(document).on('click', '.jobs-pagination-link', function(e) {
                e.preventDefault();
                currentJobsPage = parseInt($(this).data('page'));
                renderJobListings(allOpenJobs, savedJobIdsGlobal);
                $('.modal-body').animate({
                    scrollTop: 0
                }, 300);
            });

            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                const targetTab = $(e.target).attr('id');
                if (targetTab === 'lowongan-tab' && currentCompanyData) {
                    console.log('Tab switched to Lowongan');
                    currentJobsPage = 1;
                    renderJobListings(currentCompanyData.company.job_postings, savedJobIdsGlobal);
                }
            });

            // ========== JOB CARD CLICK ==========
            $(document).on('click', '.job-card-clickable', function(e) {
                if ($(e.target).closest('.btn-add-bookmark, .bookmark-job-btn').length) return;

                const jobId = $(this).data('job-id');
                const hasApplied = $(this).data('has-applied') === 'true' || $(this).data('has-applied') === true;
                console.log('🔵 Job card clicked, Job ID:', jobId, 'hasApplied:', hasApplied);

                // Close company modal if open
                if ($('#companyDetailModal').hasClass('show')) {
                    $('#companyDetailModal').modal('hide');
                    setTimeout(function() {
                        loadJobDetails(jobId, hasApplied);
                    }, 300);
                } else {
                    loadJobDetails(jobId, hasApplied);
                }
            });

            // ========== LOAD JOB DETAILS ==========
            function loadJobDetails(jobId, hasApplied) {
                console.log('🔄 Loading job details for ID:', jobId);
                currentJobId = jobId;

                $.ajax({
                    url: '/jobs/' + jobId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('✅ Job data received:', data);
                        renderJobDetails(data, hasApplied);
                        $('#jobDetailModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('❌ Error loading job:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat detail pekerjaan'
                        });
                    }
                });
            }

            // ========== RENDER JOB DETAILS ==========
            function renderJobDetails(data, hasApplied) {
                const job = data.job;
                const isApplied = data.hasApplied || hasApplied || appliedJobIdsGlobal.includes(job.id);
                const isSaved = data.isSaved || savedJobIdsGlobal.includes(job.id);

                $('#job-title').text(job.title);
                $('#company-name').text(job.company?.name || 'Company');

                // Updated At
                const updatedAt = job.updated_at ? new Date(job.updated_at) : new Date();
                const now = new Date();
                const diffDays = Math.ceil(Math.abs(now - updatedAt) / (1000 * 60 * 60 * 24));
                const timeAgo = diffDays === 0 ? 'Hari ini' : diffDays === 1 ? 'Kemarin' : diffDays + ' hari lalu';
                $('#updated-at').text('Diperbarui ' + timeAgo);

                $('#job-type-industry').html(`
                    <span class="badge bg-light text-dark me-2">${job.type_jobs?.name || 'N/A'}</span>
                    <span class="badge bg-light text-dark">${job.industry?.name || 'N/A'}</span>
                `);

                $('#job-location').html(`<i class="bi bi-geo-alt me-2"></i>${job.city?.name || 'N/A'}`);

                // Salary + Slot + Close Date + Schedule
                let salaryHTML = `
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">
                            Rp ${formatNumber(job.salary || 0)}
                        </h4>
                        <div class="mb-2">
                            <span class="badge bg-primary" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar-check me-1"></i>
                                ${job.type_salary === 'total' ? 'Total' : 'Per Hari'}
                            </span>
                        </div>
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-people-fill me-1"></i>
                            Slot Tersedia: <strong>${job.slot || 0}</strong>
                        </small>
                `;

                // Close Recruitment
                if (job.close_recruitment) {
                    const closeDate = new Date(job.close_recruitment);
                    const now = new Date();
                    const diffTime = closeDate - now;
                    const daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const formattedDate = formatDate(job.close_recruitment);

                    let statusHTML = '';
                    let textClass = '';

                    if (daysLeft > 3) {
                        statusHTML = `(${daysLeft} hari lagi)`;
                    } else if (daysLeft > 0) {
                        statusHTML = `<span class="badge bg-warning text-dark ms-1">${daysLeft} hari lagi!</span>`;
                        textClass = 'text-danger fw-bold';
                    } else if (daysLeft === 0) {
                        statusHTML = `<span class="badge bg-warning text-dark ms-1">Hari Terakhir!</span>`;
                        textClass = 'text-danger fw-bold';
                    } else {
                        statusHTML = `<span class="badge bg-danger ms-1">Sudah Ditutup</span>`;
                        textClass = 'text-muted';
                    }

                    salaryHTML += `
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar-x me-1"></i>
                            <strong>Tutup:</strong>
                            <span class="${textClass}">
                                ${formattedDate} ${statusHTML}
                            </span>
                        </small>
                    `;
                } else {
                    salaryHTML += `
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar-x me-1"></i>
                            <strong>Tutup:</strong> Belum ditentukan
                        </small>
                    `;
                }

                // Work Schedule
                if (job.jobDatess && job.jobDatess.length > 0) {
                    salaryHTML += `
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                <strong>Jadwal Kerja:</strong>
                            </small>
                    `;

                    job.jobDatess.forEach(function(jobDate) {
                        const dateFormatted = formatDate(jobDate.date);
                        const dayName = jobDate.day ? jobDate.day.name : '';
                        const startTime = jobDate.start_time ? jobDate.start_time.substring(0, 5) : '';
                        const endTime = jobDate.end_time ? jobDate.end_time.substring(0, 5) : '';

                        salaryHTML += `
                            <small class="text-muted d-block ms-3">
                                <i class="bi bi-dot"></i>
                                ${dateFormatted}
                                ${dayName ? `<span class="badge bg-info text-white ms-1" style="font-size: 0.65rem;">${dayName}</span>` : ''}
                                ${startTime && endTime ? `<span class="ms-1"><i class="bi bi-clock me-1"></i>${startTime} - ${endTime}</span>` : ''}
                            </small>
                        `;
                    });

                    salaryHTML += '</div>';
                } else {
                    salaryHTML += `
                        <small class="text-muted d-block">
                            <i class="bi bi-calendar-event me-1"></i>
                            Tanggal belum ditentukan
                        </small>
                    `;
                }

                salaryHTML += '</div>';
                $('#salary-slot').html(salaryHTML);
                $('#job-info-card').data('job-id', job.id);

                // Informasi Lowongan Tab
                $('#informasi-lowongan-content').html(`
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>Alamat
                            </h5>
                            <p class="card-text">${job.address || '<span class="text-muted">Alamat tidak tersedia</span>'}</p>
                        </div>
                    </div>
                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">
                                <i class="bi bi-file-text-fill text-primary me-2"></i>Deskripsi Pekerjaan
                            </h5>
                            <div class="card-text">${job.description || '<span class="text-muted">Deskripsi tidak tersedia</span>'}</div>
                        </div>
                    </div>
                `);

                // Kualifikasi Tab
                $('#kualifikasi-content').html(`
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="bi bi-clipboard-check-fill text-primary me-2"></i>Persyaratan
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-translate text-success me-2"></i>
                                        <strong>Bahasa English:</strong>
                                        <span class="ms-2">${job.level_english || 'Tidak diwajibkan'}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-translate text-danger me-2"></i>
                                        <strong>Bahasa Mandarin:</strong>
                                        <span class="ms-2">${job.level_mandarin || 'Tidak diwajibkan'}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-range text-info me-2"></i>
                                        <strong>Usia:</strong>
                                        <span class="ms-2">${job.min_age || 'N/A'} - ${job.max_age || 'N/A'} tahun</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-rulers text-warning me-2"></i>
                                        <strong>Tinggi Badan:</strong>
                                        <span class="ms-2">Min. ${job.min_height || 'N/A'} cm</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-speedometer2 text-secondary me-2"></i>
                                        <strong>Berat Badan:</strong>
                                        <span class="ms-2">Min. ${job.min_weight || 'N/A'} kg</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-gender-ambiguous text-primary me-2"></i>
                                        <strong>Gender:</strong>
                                        <span class="ms-2">${job.gender || 'Semua'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="bi bi-star-fill text-warning me-2"></i>Skill yang Dibutuhkan
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                ${job.skills && job.skills.length > 0 
                                    ? job.skills.map(skill => `
                                                                                                                    <span class="badge bg-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                                                                                                        <i class="bi bi-check-circle me-1"></i>${skill.name}
                                                                                                                    </span>
                                                                                                                `).join('') 
                                    : '<span class="text-muted">Tidak ada skill khusus yang dibutuhkan</span>'
                                }
                            </div>
                        </div>
                    </div>
                `);

                // Benefit Tab
                let benefitHTML = '';
                if (job.benefits && job.benefits.length > 0) {
                    benefitHTML = job.benefits.map(benefit => `
                        <div class="card shadow-sm mt-2">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-gift-fill text-success" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">${benefit.benefit?.name || 'Benefit'}</h6>
                                        <p class="text-muted mb-1">
                                            <small>
                                                <strong>Jenis:</strong>
                                                ${benefit.benefit_type === 'cash'
                                                    ? '<span class="badge bg-success">Cash</span>'
                                                    : benefit.benefit_type === 'in_kind'
                                                        ? '<span class="badge bg-info">In Kind</span>'
                                                        : '<span class="text-muted">N/A</span>'}
                                            </small>
                                        </p>
                                        <p class="text-primary mb-0">
                                            <strong>Jumlah:</strong> ${benefit.amount || '<span class="text-muted">N/A</span>'}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    benefitHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-gift text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Benefit tidak tersedia untuk posisi ini</p>
                        </div>
                    `;
                }
                $('#benefit-content').html(benefitHTML);

                // Apply Button State
                const $applyBtn = $('#apply-btn');
                if (isApplied) {
                    $applyBtn.prop('disabled', true).removeClass('btn-primary').addClass('btn-success');
                    $applyBtn.find('.apply-text').text('Sudah Melamar');
                } else {
                    $applyBtn.prop('disabled', false).removeClass('btn-success').addClass('btn-primary');
                    $applyBtn.find('.apply-text').text('Apply');
                }

                // Save Button State
                const $saveBtn = $('#save-btn-modal');
                if (isSaved) {
                    $saveBtn.removeClass('btn-outline-warning').addClass('btn-warning');
                    $saveBtn.find('i').removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                    $saveBtn.find('.save-text').text('Sudah Disimpan');
                } else {
                    $saveBtn.removeClass('btn-warning').addClass('btn-outline-warning');
                    $saveBtn.find('i').removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                    $saveBtn.find('.save-text').text('Simpan');
                }
            }

            // ========== APPLY JOB ==========
            $(document).on('click', '#apply-btn', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const jobId = $('#job-info-card').data('job-id');
                    const jobTitle = $('#job-title').text();

                    console.log('Apply button clicked, Job ID:', jobId);

                    @guest
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Login',
                        text: 'Anda harus login terlebih dahulu untuk melamar pekerjaan.',
                        showCancelButton: true,
                        confirmButtonText: 'Login Sekarang',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#14489b',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('login') }}';
                        }
                    });
                    return;
                @endguest

                if (!jobId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Job ID tidak ditemukan. Silakan refresh halaman.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Lamar Pekerjaan?',
                    html: `
                        <div class="text-start">
                            <p class="mb-2">Apakah Anda yakin ingin melamar posisi:</p>
                            <div class="alert alert-info py-2 mb-2">
                                <strong>${jobTitle}</strong>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Lamaran Anda akan dikirim ke perusahaan.
                            </small>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-send me-1"></i>Ya, Lamar Sekarang',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        performApplyJob(jobId, jobTitle);
                    }
                });
            });

        function performApplyJob(jobId, jobTitle) {
            console.log('Applying for job ID:', jobId);

            const $btn = $('#apply-btn');
            $btn.prop('disabled', true);

            Swal.fire({
                title: 'Mengirim Lamaran...',
                html: '<div class="spinner-border text-primary"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ route('apply.job') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_posting_id: jobId
                },
                success: function(response) {
                    console.log('✅ Apply success:', response);

                    if (response.success) {
                        $btn.removeClass('btn-primary').addClass('btn-success');
                        $btn.find('.apply-text').text('Sudah Melamar');

                        if (!appliedJobIdsGlobal.includes(jobId)) {
                            appliedJobIdsGlobal.push(jobId);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Melamar! 🎉',
                            html: `
                                    <div class="text-start">
                                        <p class="mb-2">${response.message}</p>
                                        <div class="alert alert-success py-2 mb-0">
                                            <i class="bi bi-envelope-check me-1"></i>
                                            <small>Cek email Anda untuk konfirmasi</small>
                                        </div>
                                    </div>
                                `,
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('❌ Apply error:', xhr);

                    $btn.prop('disabled', false);

                    let errorMessage = 'Terjadi kesalahan saat mengirim lamaran.';

                    if (xhr.status === 401) {
                        errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
                    } else if (xhr.status === 422) {
                        errorMessage = xhr.responseJSON?.message ||
                            'Anda sudah melamar pekerjaan ini sebelumnya.';
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Melamar',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }

        // ========== BOOKMARK JOB (FROM COMPANY MODAL & FAVORIT TAB) ==========
        $(document).on('click', '.bookmark-job-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const jobId = parseInt($(this).data('job-id'));
            const $icon = $(this).find('i');
            const isSaved = $icon.hasClass('bi-bookmark-fill');

            if (isSaved) {
                unsaveJob(jobId, $icon);
            } else {
                saveJob(jobId, $icon);
            }
        });

        // ========== SAVE/UNSAVE FROM MODAL BUTTON ==========
        $(document).on('click', '#saved-jobs-list .btn-add-bookmark', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const jobId = parseInt($(this).data('job-id'));
            const $icon = $(this).find('i');

            console.log('🗑️ Klik UNSAVE dari tab favorit, Job ID:', jobId);

            unsaveJob(jobId, $icon);
        });

        function saveJob(jobId, $icon) {
            console.log('💾 Saving job, ID:', jobId);

            $.ajax({
                url: '{{ route('save.job') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_posting_id: jobId
                },
                success: function(response) {
                    console.log('✅ Save success:', response);
                    if (response.success) {
                        $icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill').css('color', '#ffc107');

                        if (!savedJobIdsGlobal.includes(jobId)) {
                            savedJobIdsGlobal.push(jobId);
                        }

                        updateAllSaveButtons(jobId, true);
                        showToast('success', 'Berhasil!', 'Pekerjaan disimpan');
                    }
                },
                error: function(xhr) {
                    console.error('❌ Save error:', xhr);
                    showToast('error', 'Gagal', xhr.responseJSON?.message || 'Gagal menyimpan');
                }
            });
        }

        function unsaveJob(jobId, $icon) {
            console.log('🗑️ Unsaving job, ID:', jobId);

            $.ajax({
                url: '{{ route('unsave.job-history') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_posting_id: jobId
                },
                success: function(response) {
                    console.log('✅ Unsave success:', response);
                    if (response.success) {
                        $icon.removeClass('bi-bookmark-fill').addClass('bi-bookmark').css('color', '#6c757d');

                        const index = savedJobIdsGlobal.indexOf(jobId);
                        if (index > -1) savedJobIdsGlobal.splice(index, 1);

                        updateAllSaveButtons(jobId, false);

                        // ✅ HAPUS CARD DARI TAB FAVORIT
                        $(`.job-card-clickable[data-job-id="${jobId}"]`).closest('[data-saved-id]').fadeOut(300,
                            function() {
                                $(this).remove();

                                // ✅ CEK JIKA TIDAK ADA CARD LAGI
                                if ($('#saved-jobs-list').children().length === 0) {
                                    location.reload();
                                }
                            });

                        showToast('success', 'Berhasil!', 'Pekerjaan dihapus dari favorit');
                    }
                },
                error: function(xhr) {
                    console.error('❌ Unsave error:', xhr);
                    showToast('error', 'Gagal', xhr.responseJSON?.message || 'Gagal menghapus');
                }
            });
        }

        function updateAllSaveButtons(jobId, isSaved) {
            // Update modal button
            const $modalBtn = $('#save-btn-modal');
            const currentJobId = parseInt($('#job-info-card').data('job-id'));

            if (currentJobId === jobId) {
                if (isSaved) {
                    $modalBtn.removeClass('btn-outline-warning').addClass('btn-warning');
                    $modalBtn.find('i').removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                    $modalBtn.find('.save-text').text('Sudah Disimpan');
                } else {
                    $modalBtn.removeClass('btn-warning').addClass('btn-outline-warning');
                    $modalBtn.find('i').removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                    $modalBtn.find('.save-text').text('Simpan');
                }
            }

            // Update all bookmark buttons
            $(`.bookmark-job-btn[data-job-id="${jobId}"], .btn-add-bookmark[data-job-id="${jobId}"]`).each(function() {
                const $icon = $(this).find('i');
                if (isSaved) {
                    $icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill').css('color', '#ffc107');
                } else {
                    $icon.removeClass('bi-bookmark-fill').addClass('bi-bookmark').css('color', '#6c757d');
                }
            });
        }

        // ========== UNSUBSCRIBE FROM ACTIVITY PAGE ==========
        $(document).on('click', '.unsubscribe-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            const companyId = $btn.data('company-id');
            const companyName = $btn.data('company-name');
            const $card = $btn.closest('[data-subscribe-id]');

            Swal.fire({
                title: 'Unsubscribe?',
                text: `Berhenti mengikuti "${companyName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('activity.unsubscribe') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            company_id: companyId
                        },
                        success: function(response) {
                            showToast('success', 'Berhasil!', response.message);
                            $card.fadeOut(300, function() {
                                $(this).remove();
                                if ($('#subscribed-companies-list').children()
                                    .length === 0) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr) {
                            showToast('error', 'Gagal', 'Terjadi kesalahan');
                        }
                    });
                }
            });
        });

        // ========== ACTIVE TAB MEMORY ==========
        const activeTab = localStorage.getItem('activeActivityTab');
        if (activeTab) {
            $('#' + activeTab).tab('show');
        }

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            localStorage.setItem('activeActivityTab', e.target.id);
        });

        // ========== MODAL RESET ==========
        $('#companyDetailModal').on('hidden.bs.modal', function() {
            currentCompanyData = null;
            currentCompanyId = null;
            isSubscribed = false;
            currentJobsPage = 1;
            allOpenJobs = [];
            $('#informasi-tab').tab('show');
        });

        $('#jobDetailModal').on('hidden.bs.modal', function() {
            currentJobId = null;
            $('#informasi-lowongan-tab').tab('show');
        });

        console.log('✅ All event listeners registered successfully');
        });
    </script>
@endsection
