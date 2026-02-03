@extends('layouts.main')

@section('title', 'Kelola Lowongan')
<style>
    /* Clickable row */
    .clickable-row {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .clickable-row:hover {
        background-color: rgba(102, 126, 234, 0.05) !important;
        transform: translateX(3px);
    }

    /* Job detail styles */
    .job-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
    }

    .job-detail-header h3,
    .job-detail-header p {
        color: white !important;
    }

    .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid #667eea;
    }

    .info-card h5 {
        color: #667eea;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .job-description {
        line-height: 1.8;
    }

    /* Badge styles */
    .badge-open {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 8px 16px;
    }

    .badge-closed {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 8px 16px;
    }

    .badge-draft {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 8px 16px;
    }

    .badge-approved {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 8px 16px;
    }

    .badge-rejected {
        background: linear-gradient(135deg, #fa709a, #fee140);
        color: white;
        padding: 8px 16px;
    }

    .badge-pending {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        padding: 8px 16px;
    }

    :root {
        --primary-blue: #14489b;
        --secondary-blue: #244770;
        --dark-blue: #1e3992;
        --light-blue: #dbeafe;
        --bg-blue: #eff6ff;
    }

    body {
        background-color: #f8f9fa;
    }

    .stat-card {
        border-left: 4px solid var(--primary-blue);
        transition: all 0.3s;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-card .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    /* BADGE DENGAN KONTRAS TINGGI & JELAS */
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        border: 2px solid;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .badge-open {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: #047857;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .badge-closed {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-color: #b91c1c;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .badge-draft {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border-color: #374151;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
    }

    .badge-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border-color: #b45309;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .badge-approved {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border-color: #1d4ed8;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .badge-rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-color: #b91c1c;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .badge-status i {
        font-size: 1rem;
    }

    .table thead th {
        background: linear-gradient(135deg, var(--bg-blue), var(--light-blue));
        color: var(--primary-blue);
        font-weight: 600;
        border: none;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid #e5e7eb;
    }

    .table tbody tr:hover {
        background-color: var(--bg-blue);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(20, 72, 155, 0.1);
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        border: none;
        color: white;
        transition: all 0.3s;
        box-shadow: 0 4px 8px rgba(20, 72, 155, 0.2);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(20, 72, 155, 0.3);
    }

    .job-title {
        font-weight: 600;
        color: var(--primary-blue);
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .job-meta {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .btn-group .btn {
        border-radius: 0;
        transition: all 0.2s;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
        z-index: 1;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 5rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }

    .search-filter-section {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* ANIMATION STYLES */
    .table-container {
        position: relative;
        min-height: 400px;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    .fade-out {
        animation: fadeOut 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }

    .slide-in-left {
        animation: slideInLeft 0.5s ease-out;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .slide-in-right {
        animation: slideInRight 0.5s ease-out;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ✅ LOADING OVERLAY - HANYA DI AREA TABLE */
    .table-loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 100;
        border-radius: 8px;
        backdrop-filter: blur(4px);
    }

    .table-loading-overlay .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.4rem;
        border-color: var(--primary-blue);
        border-right-color: transparent;
    }

    .table-loading-text {
        margin-top: 1rem;
        color: var(--primary-blue);
        font-weight: 600;
        font-size: 1rem;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .loading-dots {
        animation: pulse 1.5s ease-in-out infinite;
    }

    .pagination {
        margin-top: 1.5rem;
    }

    .pagination .page-link {
        color: var(--primary-blue);
        border-color: #e5e7eb;
        transition: all 0.2s;
    }

    .pagination .page-link:hover {
        background-color: var(--light-blue);
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card {
        border-radius: 12px;
        border: none;
        overflow: hidden;
    }
</style>

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1" style="color: var(--primary-blue)">
                            <i class="bi bi-briefcase-fill me-2"></i>Kelola Lowongan Kerja
                        </h3>
                        <p class="text-muted mb-0">Manajemen semua lowongan pekerjaan perusahaan Anda</p>
                    </div>
                    <a href="{{ route('company.jobs.create') }}" class="btn btn-primary-custom px-4">
                        <i class="bi bi-plus-lg me-2"></i>Buat Lowongan Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4" id="statsCards">
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Lowongan</h6>
                            <h3 class="fw-bold mb-0" style="color: var(--primary-blue)" id="totalJobs">{{ $jobs->total() }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: linear-gradient(135deg, #10b981, #059669)">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Lowongan Aktif</h6>
                            <h3 class="fw-bold mb-0 text-success" id="activeJobs">
                                {{ $jobs->where('status', 'Open')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: linear-gradient(135deg, #f59e0b, #d97706)">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Draft</h6>
                            <h3 class="fw-bold mb-0 text-warning" id="draftJobs">
                                {{ $jobs->where('status', 'Draft')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: linear-gradient(135deg, #3b82f6, #2563eb)">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Pelamar</h6>
                            <h3 class="fw-bold mb-0 text-info" id="totalApplicants">
                                {{ $jobs->sum(fn($job) => $job->applications->count()) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div id="alertContainer">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Search & Filter -->
        <div class="search-filter-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Cari lowongan..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="Open">Open</option>
                        <option value="Closed">Closed</option>
                        <option value="Draft">Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="verificationFilter">
                        <option value="">Semua Verifikasi</option>
                        <option value="Approved">Approved</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Job Listings Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-container" id="tableContainer">
                    <div id="jobsTableWrapper">
                        @if ($jobs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-geo-alt me-2"></i>Lokasi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                            <th><i class="bi bi-people me-2"></i>Pelamar</th>
                                            <th><i class="bi bi-toggle-on me-2"></i>Status</th>
                                            <th><i class="bi bi-shield-check me-2"></i>Verifikasi</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jobs as $job)
                                            <tr class="clickable-row" data-job-id="{{ $job->id }}">
                                                <td>
                                                    <div class="job-title">{{ $job->title }}</div>
                                                    <small class="job-meta">
                                                        <i
                                                            class="bi bi-tag me-1"></i>{{ $job->industry ? $job->industry->name : '-' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <i class="bi bi-pin-map text-muted me-1"></i>
                                                    {{ $job->city ? $job->city->name : '-' }}
                                                </td>
                                                <td>
                                                    <small>{{ $job->created_at->format('d M Y') }}</small><br>
                                                    <small
                                                        class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-person me-1"></i>{{ $job->applications->count() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($job->status == 'Open')
                                                        <span class="badge badge-open badge-status">
                                                            <i class="bi bi-check-circle"></i>Open
                                                        </span>
                                                    @elseif($job->status == 'Closed')
                                                        <span class="badge badge-closed badge-status">
                                                            <i class="bi bi-x-circle"></i>Closed
                                                        </span>
                                                    @else
                                                        <span class="badge badge-draft badge-status">
                                                            <i class="bi bi-file-earmark"></i>Draft
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($job->verification_status == 'Approved')
                                                        <span class="badge badge-approved badge-status">
                                                            <i class="bi bi-shield-check"></i>Approved
                                                        </span>
                                                    @elseif($job->verification_status == 'Rejected')
                                                        <span class="badge badge-rejected badge-status">
                                                            <i class="bi bi-shield-x"></i>Rejected
                                                        </span>
                                                    @else
                                                        <span class="badge badge-pending badge-status">
                                                            <i class="bi bi-clock"></i>Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('company.jobs.show', $job->id) }}"
                                                            class="btn btn-sm btn-outline-primary btn-view-job"
                                                            data-job-id="{{ $job->id }}" title="Detail"
                                                            data-bs-toggle="tooltip">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('company.jobs.edit', $job->id) }}"
                                                            class="btn btn-sm btn-outline-warning" title="Edit"
                                                            data-bs-toggle="tooltip">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger delete-btn"
                                                            data-id="{{ $job->id }}"
                                                            data-title="{{ $job->title }}" title="Hapus"
                                                            data-bs-toggle="tooltip">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $jobs->links() }}
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h5 class="text-muted fw-bold">Tidak ada data</h5>
                                <p class="text-muted">Tidak ditemukan lowongan sesuai filter Anda</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <!-- Job Detail Modal -->
    <div class="modal fade" id="jobDetailModal" tabindex="-1" aria-labelledby="jobDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="jobDetailModalLabel">
                        <i class="bi bi-briefcase-fill me-2"></i>Detail Lowongan Pekerjaan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading State -->
                    <div id="jobDetailLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-3">Memuat detail lowongan...</p>
                    </div>

                    <!-- Content -->
                    <div id="jobDetailContent" style="display: none;"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Tutup
                    </button>
                    <a href="#" id="editJobBtn" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Lowongan
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let currentPage = 1;
        let searchQuery = '';
        let statusFilter = '';
        let verificationFilter = '';

        // ✅ CHECK FOR SUCCESS/ERROR ON PAGE LOAD
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil! ',
                html: '<div style="font-size: 1.1rem;">{{ session('success') }}</div>',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal! ',
                html: '<div style="font-size: 1.1rem;">{{ session('error') }}</div>',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif

        // ========== AJAX LOAD JOBS ==========
        function loadJobs(page, animation = 'fade', updateStats = false) {
            const container = $('#tableContainer');
            const wrapper = $('#jobsTableWrapper');

            if (!$('.table-loading-overlay').length) {
                container.append(`
                <div class="table-loading-overlay">
                    <div class="spinner-border"></div>
                    <div class="table-loading-text loading-dots">Memuat data...</div>
                </div>
            `);
            }

            wrapper.addClass('fade-out');

            $.ajax({
                url: '{{ route('company.jobs.index') }}',
                method: 'GET',
                data: {
                    page: page,
                    search: searchQuery,
                    status: statusFilter,
                    verification: verificationFilter,
                    ajax: 1
                },
                success: function(response) {
                    setTimeout(() => {
                        $('.table-loading-overlay').fadeOut(200, function() {
                            $(this).remove();
                        });

                        wrapper.removeClass('fade-out');

                        if (animation === 'slide-left') {
                            wrapper.addClass('slide-in-left');
                        } else if (animation === 'slide-right') {
                            wrapper.addClass('slide-in-right');
                        } else {
                            wrapper.addClass('fade-in');
                        }

                        wrapper.html(response.html);

                        setTimeout(() => {
                            wrapper.removeClass(
                                'fade-in slide-in-left slide-in-right');
                        }, 500);

                        if (updateStats && response.stats) {
                            $('#totalJobs').text(response.stats.total);
                            $('#activeJobs').text(response.stats.active);
                            $('#draftJobs').text(response.stats.draft);
                            $('#totalApplicants').text(response.stats.applicants);
                        }

                        initializeEventHandlers();

                        $('html, body').animate({
                            scrollTop: container.offset().top - 100
                        }, 400);

                    }, 300);
                },
                error: function(xhr) {
                    $('.table-loading-overlay').fadeOut(200, function() {
                        $(this).remove();
                    });
                    wrapper.removeClass('fade-out');

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal memuat data. Silakan coba lagi.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }

        // ========== PAGINATION ==========
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            const currentPageNum = currentPage;
            currentPage = page;

            let animation = 'fade';
            if (page > currentPageNum) {
                animation = 'slide-left';
            } else if (page < currentPageNum) {
                animation = 'slide-right';
            }

            loadJobs(page, animation, true);
        });

        // ========== SEARCH ==========
        let searchTimeout;
        $('#searchInput').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchQuery = $(this).val();

            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadJobs(1, 'fade', false);
            }, 500);
        });

        // ========== FILTERS ==========
        $('#statusFilter').on('change', function() {
            statusFilter = $(this).val();
            currentPage = 1;
            loadJobs(1, 'fade', true);
        });

        $('#verificationFilter').on('change', function() {
            verificationFilter = $(this).val();
            currentPage = 1;
            loadJobs(1, 'fade', true);
        });

        // ========== ROW CLICK HANDLER ==========
        $(document).on('click', '.clickable-row', function(e) {
            // Jangan trigger jika user klik button, badge, atau link
            if ($(e.target).closest('button, .badge, a, .btn-group').length === 0) {
                const jobId = $(this).data('job-id');

                console.log('🖱️ Row clicked, Job ID:', jobId);

                if (!jobId) {
                    console.error('❌ Invalid job ID');
                    return;
                }

                loadJobDetail(jobId);
            }
        });

        // ========== BUTTON VIEW CLICK HANDLER ==========
        $(document).on('click', '.btn-view-job', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const jobId = $(this).data('job-id');

            console.log('🔘 View button clicked, Job ID:', jobId);

            if (!jobId) {
                console.error('❌ Invalid job ID');
                return;
            }

            loadJobDetail(jobId);
        });

        // ========== LOAD JOB DETAIL FUNCTION ==========
        function loadJobDetail(jobId) {
            console.log('🔍 Loading job detail for ID:', jobId);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('jobDetailModal'));
            modal.show();

            // Show loading
            $('#jobDetailLoading').show();
            $('#jobDetailContent').hide();

            // AJAX request
            $.ajax({
                url: `/company/jobs/${jobId}/detail`,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('✅ Job detail loaded:', response);

                    if (response.success) {
                        renderJobDetail(response.data);
                        $('#jobDetailLoading').hide();
                        $('#jobDetailContent').show();

                        // Update edit button URL
                        $('#editJobBtn').attr('href', `/company/jobs/${jobId}/edit`);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat detail lowongan',
                            confirmButtonColor: '#dc3545'
                        });
                        modal.hide();
                    }
                },
                error: function(xhr) {
                    console.error('❌ Error loading job detail:', xhr);

                    let errorMessage = 'Terjadi kesalahan saat memuat detail lowongan';

                    if (xhr.status === 404) {
                        errorMessage = 'Lowongan tidak ditemukan';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Anda tidak memiliki akses ke lowongan ini';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });

                    modal.hide();
                }
            });
        }

        // ========== RENDER JOB DETAIL FUNCTION ==========
        function renderJobDetail(job) {
            const statusBadges = {
                'Open': '<span class="badge badge-open"><i class="bi bi-check-circle me-1"></i>Open</span>',
                'Closed': '<span class="badge badge-closed"><i class="bi bi-x-circle me-1"></i>Closed</span>',
                'Draft': '<span class="badge badge-draft"><i class="bi bi-file-earmark me-1"></i>Draft</span>'
            };

            const verificationBadges = {
                'Approved': '<span class="badge badge-approved"><i class="bi bi-shield-check me-1"></i>Approved</span>',
                'Rejected': '<span class="badge badge-rejected"><i class="bi bi-shield-x me-1"></i>Rejected</span>',
                'Pending': '<span class="badge badge-pending"><i class="bi bi-clock me-1"></i>Pending</span>'
            };

            // ✅ Format Salary Type
            const salaryTypeText = {
                'total': 'Total',
                'per_day': 'Per Hari'
            };

            const salaryType = job.type_salary ? salaryTypeText[job.type_salary] : 'Total';

            // ✅ Format Benefits
            let benefitsHTML = '';
            if (job.benefits && job.benefits.length > 0) {
                benefitsHTML = `
            <div class="info-card mb-3">
                <h5><i class="bi bi-gift me-2"></i>Benefit & Fasilitas</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="40%">Benefit</th>
                                <th width="30%">Tipe</th>
                                <th width="30%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${job.benefits.map(benefit => `
                                <tr>
                                    <td>
                                        <i class="bi bi-gift-fill text-primary me-2"></i>
                                        <strong>${benefit.benefit?.name || benefit.name || '-'}</strong>
                                    </td>
                                    <td>
                                        ${benefit.benefit_type ? `
                                            <span class="badge ${benefit.benefit_type === 'cash' ? 'bg-success' : 'bg-info'}">
                                                <i class="bi ${benefit.benefit_type === 'cash' ? 'bi-cash-coin' : 'bi-box-seam'} me-1"></i>
                                                ${benefit.benefit_type === 'cash' ? 'Cash' : 'In Kind'}
                                            </span>
                                        ` : '<span class="text-muted">-</span>'}
                                    </td>
                                    <td>
                                        ${benefit.amount ? `<strong>${benefit.amount}</strong>` : '<span class="text-muted">-</span>'}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
            }

            // ✅ Format Job Dates
            let jobDatesHTML = '';
            if (job.jobDatess && job.jobDatess.length > 0) {
                jobDatesHTML = `
            <div class="info-card mb-3">
                <h5><i class="bi bi-calendar-week me-2"></i>Jadwal Kerja</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="25%">Hari</th>
                                <th width="25%">Tanggal</th>
                                <th width="25%">Jam Mulai</th>
                                <th width="25%">Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${job.jobDatess.map(jobDate => `
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar-day text-primary me-2"></i>
                                        <strong>${jobDate.day?.name || '-'}</strong>
                                    </td>
                                    <td>
                                        ${formatDate(jobDate.date)}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="bi bi-clock me-1"></i>
                                            ${formatTime(jobDate.start_time)}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock-fill me-1"></i>
                                            ${formatTime(jobDate.end_time)}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
            }

            let html = `
        <div class="job-detail-header mb-4 p-4 bg-light rounded">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2">${job.title}</h3>
                    <p class="mb-2">
                        <i class="bi bi-building me-2"></i>${job.company?.name || '-'}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-geo-alt me-2"></i>${job.city?.name || '-'}
                        <span class="ms-3"><i class="bi bi-tag me-2"></i>${job.industry?.name || '-'}</span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="mb-2">
                        ${statusBadges[job.status] || '<span class="badge bg-secondary">Unknown</span>'}
                    </div>
                    <div>
                        ${verificationBadges[job.verification_status] || '<span class="badge bg-secondary">Unknown</span>'}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-card mb-3">
                    <h5><i class="bi bi-info-circle me-2"></i>Informasi Dasar</h5>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%"><strong>Tipe Pekerjaan:</strong></td>
                            <td>${job.type_jobs?.name || '-'}</td>
                        </tr>
                        <tr>
                            <td><strong>Gaji:</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span>Rp ${formatNumber(job.salary || 0)}</span>
                                    <span class="badge bg-primary">
                                        <i class="bi bi-calendar-check me-1"></i>${salaryType}
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Slot Tersedia:</strong></td>
                            <td>${job.slot || 0} orang</td>
                        </tr>
                        <tr>
                            <td><strong>Total Pelamar:</strong></td>
                            <td><span class="badge bg-primary">${job.applications_count || 0} pelamar</span></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Posting:</strong></td>
                            <td>${formatDate(job.created_at)}</td>
                        </tr>
                        <tr>
                            <td><strong>Buka Rekrutmen:</strong></td>
                            <td>${formatDate(job.open_recruitment)}</td>
                        </tr>
                        <tr>
                            <td><strong>Tutup Rekrutmen:</strong></td>
                            <td>${formatDate(job.close_recruitment)}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card mb-3">
                    <h5><i class="bi bi-person-badge me-2"></i>Persyaratan</h5>
                    <table class="table table-sm">
                        <tr>
                            <td width="40%"><strong>Jenis Kelamin:</strong></td>
                            <td>${job.gender || 'Tidak ada preferensi'}</td>
                        </tr>
                        <tr>
                            <td><strong>Usia:</strong></td>
                            <td>${job.min_age || 0} - ${job.max_age || 0} tahun</td>
                        </tr>
                        <tr>
                            <td><strong>Tinggi Badan:</strong></td>
                            <td>Min. ${job.min_height || 0} cm</td>
                        </tr>
                        <tr>
                            <td><strong>Berat Badan:</strong></td>
                            <td>Min. ${job.min_weight || 0} kg</td>
                        </tr>
                        <tr>
                            <td><strong>Bahasa Inggris:</strong></td>
                            <td>
                                <span class="badge bg-info">
                                    ${job.level_english ? job.level_english.charAt(0).toUpperCase() + job.level_english.slice(1) : '-'}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Bahasa Mandarin:</strong></td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    ${job.level_mandarin ? job.level_mandarin.charAt(0).toUpperCase() + job.level_mandarin.slice(1) : '-'}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Wawancara:</strong></td>
                            <td>
                                ${job.has_interview ? 
                                    '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Ya</span>' : 
                                    '<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Tidak</span>'}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="info-card mb-3">
            <h5><i class="bi bi-file-text me-2"></i>Deskripsi Pekerjaan</h5>
            <div class="job-description">
                ${job.description || '<p class="text-muted">Tidak ada deskripsi</p>'}
            </div>
        </div>

        ${job.skills && job.skills.length > 0 ? `
            <div class="info-card mb-3">
                <h5><i class="bi bi-tools me-2"></i>Keahlian yang Dibutuhkan</h5>
                <div class="d-flex flex-wrap gap-2">
                    ${job.skills.map(skill => `
                        <span class="badge bg-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-check-circle me-1"></i>${skill.name}
                        </span>
                    `).join('')}
                </div>
            </div>
        ` : ''}

        ${benefitsHTML}

        ${jobDatesHTML}
    `;

            $('#jobDetailContent').html(html);
        }

        function formatTime(timeString) {
            if (!timeString) return '-';

            // Handle both "HH:MM:SS" and "HH:MM" formats
            const parts = timeString.split(':');
            return `${parts[0]}:${parts[1]}`;
        }
        // ========== HELPER FUNCTIONS ==========
        function formatNumber(num) {
            if (!num) return '0';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }


        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return date.toLocaleDateString('id-ID', options);
        }

        // ========== DELETE HANDLER ==========
        function initializeEventHandlers() {
            $('[data-bs-toggle="tooltip"]').tooltip();

            $('.delete-btn').off('click').on('click', function(e) {
                e.stopPropagation(); // Prevent row click

                const jobId = $(this).data('id');
                const jobTitle = $(this).data('title');

                Swal.fire({
                    title: 'Hapus Lowongan?',
                    html: `
                    <div class="text-start">
                        <p class="mb-3">Apakah Anda yakin ingin menghapus lowongan:</p>
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-briefcase me-2"></i><strong>${jobTitle}</strong>
                        </div>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan
                        </div>
                    </div>
                `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
                    cancelButtonText: '<i class="bi bi-x-lg me-1"></i> Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            html: '<div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });

                        const form = $('#deleteForm');
                        form.attr('action', `{{ url('company/jobs') }}/${jobId}`);
                        form.submit();
                    }
                });
            });
        }

        initializeEventHandlers();

        setTimeout(() => {
            $('#alertContainer .alert').fadeOut('slow');
        }, 1000);
    });
</script>
