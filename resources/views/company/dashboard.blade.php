@extends('layouts.main')

@section('content')

    <style>
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

        .badge-approved {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .badge-rejected-verification {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-color: #b91c1c;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .badge-pending-verification {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-color: #b45309;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        /* Badge withdrawn */
        .badge-withdrawn {
            background: linear-gradient(135deg, #6c757d 0%, #343a40 100%);
            color: white;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Badge interviewed */
        .badge-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Badge not interviewed */
        .badge-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Clickable row styling */
        .clickable-row {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Prevent text selection on double click */
        .clickable-row {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .tooltip-inner {
            max-width: 280px;
            text-align: left;
            padding: 0.75rem;
            background-color: #2c3e50;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .tooltip-inner hr {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 0.5rem 0;
        }

        .tooltip-arrow::before {
            border-top-color: #2c3e50 !important;
        }

        /* ========== SLOT INDICATOR ========== */
        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            transition: width 0.6s ease;
            background: linear-gradient(90deg,
                    rgba(255, 255, 255, 0.1) 0%,
                    rgba(255, 255, 255, 0) 100%);
        }

        /* Slot badge styling */
        .badge.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        }

        .badge {
            transition: all 0.3s ease;
            cursor: help;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        /* Table cell alignment */
        td .d-flex.flex-column {
            gap: 0.25rem;
        }

        .avatar-circle {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* ========== BADGE STYLING ========== */

        .badge {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
        }

        .badge-pending {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #000;
        }

        .badge-accepted {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
        }

        .badge-rejected {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: #fff;
        }

        .badge-withdrawn {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: #fff;
        }

        .badge-finished {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: #fff;
        }

        .badge-invited {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #fff;
        }

        /* ========== MODAL CONTENT ========== */
        .candidate-profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .candidate-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
        }

        .info-card h5 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .status-selector {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            border: 2px solid #e9ecef;
        }

        /* ========== ACTION BUTTONS ========== */
        .action-btn {
            transition: all 0.3s ease;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        body {
            background-color: #f8f9fa;
        }

        /* Header Styles */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(20, 72, 155, 0.3);
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            border-left: 4px solid var(--primary-blue);
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        /* Badges */
        .badge-status {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .badge-accepted {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-rejected {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .badge-finished {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .badge-withdrawn {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }

        .badge-invited {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        /* Tabs */
        .nav-tabs {
            border-bottom: 2px solid #e5e7eb;
        }

        .nav-tabs .nav-link {
            color: #6b7280;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-blue);
            border-bottom-color: var(--primary-blue);
            background: transparent;
        }

        .nav-tabs .nav-link .badge {
            margin-left: 0.5rem;
        }

        /* Table */
        .table thead th {
            background: var(--light-blue);
            color: var(--primary-blue);
            font-weight: 700;
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

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table-loading-text {
            margin-top: 1rem;
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 1rem;
        }

        /* Table Container */
        .table-container {
            position: relative;
            min-height: 400px;
        }

        /* Avatar */
        .avatar-circle {
            width: 45px;
            height: 45px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            box-shadow: 0 4px 8px rgba(20, 72, 155, 0.3);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 8px rgba(20, 72, 155, 0.2);
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        /* Card */
        .card {
            border-radius: 12px;
            border: none;
        }

        /* Pagination */
        .pagination .page-link {
            color: var(--primary-blue);
            border-color: #e5e7eb;
            transition: all 0.2s;
        }
    </style>

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="fw-bold mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard Perusahaan</h3>
                    <p class="mb-0 opacity-90">Kelola lowongan dan pelamar Anda</p>
                </div>
                <button class="btn btn-light" onclick="window.location.href='{{ route('company.jobs.create') }}'">
                    <i class="bi bi-plus-lg me-2"></i>Buat Lowongan Baru
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Lowongan</h6>
                            <h2 class="fw-bold mb-0" style="color: var(--primary-blue)" id="statTotalJobs">0</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: linear-gradient(135deg, #10b981, #059669)">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Pelamar</h6>
                            <h2 class="fw-bold mb-0 text-success" id="statTotalApplicants">0</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: linear-gradient(135deg, #3b82f6, #2563eb)">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Kandidat Diterima</h6>
                            <h2 class="fw-bold mb-0 text-primary" id="statAccepted">0</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon me-3" style="background: linear-gradient(135deg, #f59e0b, #d97706)">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Menunggu Verifikasi</h6>
                            <h2 class="fw-bold mb-0 text-warning" id="statPending">0</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Search & Filter -->
                <div class="filter-section">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Cari lowongan" id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                        <!-- ✅ BARU: Filter Verification Status -->
                        <div class="col-md-2">
                            <select class="form-select" id="filterVerification">
                                <option value="">Semua Verifikasi</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="sortBy">
                                <option value="newest">Urutkan: Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="name_asc">Nama A-Z</option>
                                <option value="name_desc">Nama Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Tab Semua Lowongan -->
                    <div class="tab-pane fade show active" id="semua">
                        <div class="table-container" id="tableContainerSemua">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-briefcase me-2"></i>Judul</th>
                                            <th><i class="bi bi-geo-alt me-2"></i>Lokasi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                            <th><i class="bi bi-people me-2"></i>Pelamar</th>
                                            <th><i class="bi bi-person-check me-2"></i>Slot</th>
                                            <th><i class="bi bi-toggle-on me-2"></i>Status</th>
                                            <th><i class="bi bi-shield-check me-2"></i>Verifikasi</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableSemuaBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationSemua"></div>
                        </div>
                    </div>


                    <!-- Tab Pelamar Via Undangan -->
                    <div class="tab-pane fade" id="pelamar">
                        <div class="table-container" id="tableContainerPelamar">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                            <th><i class="bi bi-shield me-2"></i>Status</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablePelamarBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationPelamar"></div>
                        </div>
                    </div>

                    <!-- Tab Belum Diproses -->
                    <div class="tab-pane fade" id="belum-diproses">
                        <div class="table-container" id="tableContainerPending">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                            <th><i class="bi bi-star me-2"></i>Poin</th>
                                            {{-- <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="tablePendingBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationPending"></div>
                        </div>
                    </div>

                    {{-- ========== MODAL DETAIL KANDIDAT ========== --}}
                    <div class="modal fade" id="candidateDetailModal" tabindex="-1"
                        aria-labelledby="candidateDetailModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="candidateDetailModalLabel">
                                        <i class="bi bi-person-badge me-2"></i>Detail Kandidat
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body" id="candidateDetailContent">
                                    <div id="detailContent" style="display: none;"></div>
                                </div>

                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-2"></i>Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ TAB CONTENT: Telah Interview -->
                    <div class="tab-pane fade" id="telah-interview">
                        <div class="table-container" id="tableContainerInterviewed">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal Interview</th>
                                            <th><i class="bi bi-shield me-2"></i>Status</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableInterviewedBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationInterviewed"></div>
                        </div>
                    </div>

                    <!-- ✅ TAB CONTENT: Belum Interview -->
                    <div class="tab-pane fade" id="belum-interview">
                        <div class="table-container" id="tableContainerNotInterviewed">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal Melamar</th>
                                            <th><i class="bi bi-info-circle me-2"></i>Status</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNotInterviewedBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationNotInterviewed"></div>
                        </div>
                    </div>


                    <!-- Tab Diterima -->
                    <div class="tab-pane fade" id="diterima">
                        <div class="table-container" id="tableContainerDiterima">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableDiterimaBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationDiterima"></div>
                        </div>
                    </div>
                    <!-- Tab Ditolak -->
                    <div class="tab-pane fade" id="ditolak">
                        <div class="table-container" id="tableContainerDitolak">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableDitolakBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationDitolak"></div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="finished">
                        <div class="table-container" id="tableContainerFinished">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal Selesai</th>
                                            <th><i class="bi bi-shield me-2"></i>Status</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableFinishedBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationFinished"></div>
                        </div>
                    </div>
                    <!-- ✅ TAB CONTENT: Withdrawn (Mengundurkan Diri) -->
                    <div class="tab-pane fade" id="withdrawn">
                        <div class="table-container" id="tableContainerWithdrawn">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-person me-2"></i>Kandidat</th>
                                            <th><i class="bi bi-briefcase me-2"></i>Posisi</th>
                                            <th><i class="bi bi-calendar me-2"></i>Tanggal Mengundurkan Diri</th>
                                            <th><i class="bi bi-chat-left-text me-2"></i>Alasan</th>
                                            <th class="text-center"><i class="bi bi-gear me-2"></i>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableWithdrawnBody"></tbody>
                                </table>
                            </div>
                            <div id="paginationWithdrawn"></div>
                        </div>
                    </div>

                    <!-- Modal Kirim Email -->
                    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="sendEmailModalLabel">
                                        <i class="bi bi-send me-2"></i>Kirim Email ke Kandidat
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="emailForm">
                                        <input type="hidden" id="emailApplicationId">

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kepada:</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                <input type="text" class="form-control" id="emailRecipientName"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email:</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                <input type="email" class="form-control" id="emailRecipientEmail"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Subjek Email: <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="emailSubject"
                                                placeholder="Contoh: Terima kasih atas kerja sama Anda" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Pesan: <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="emailMessage" rows="8" placeholder="Tulis pesan Anda di sini..." required></textarea>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Gunakan template atau tulis pesan custom untuk kandidat
                                            </small>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="bi bi-lightbulb me-2"></i>
                                            <strong>Template Tersedia:</strong>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-info me-2"
                                                    onclick="useTemplate('thanks')">
                                                    Ucapan Terima Kasih
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info me-2"
                                                    onclick="useTemplate('feedback')">
                                                    Feedback Positif
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                    onclick="useTemplate('certificate')">
                                                    Sertifikat/Dokumen
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i>Batal
                                    </button>
                                    <button type="button" class="btn btn-primary" id="sendEmailBtn">
                                        <i class="bi bi-send me-1"></i>Kirim Email
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        console.log('✅ Dashboard initialized');

        // ========== STATE MANAGEMENT ==========
        let state = {
            currentTab: localStorage.getItem('dashboard_tab') || 'semua',
            currentPage: {
                semua: parseInt(localStorage.getItem('dashboard_page_semua')) || 1,
                pelamar: parseInt(localStorage.getItem('dashboard_page_pelamar')) || 1,
                pending: parseInt(localStorage.getItem('dashboard_page_pending')) || 1,
                interviewed: parseInt(localStorage.getItem('dashboard_page_interviewed')) || 1,
                notInterviewed: parseInt(localStorage.getItem('dashboard_page_not_interviewed')) || 1,
                accepted: parseInt(localStorage.getItem('dashboard_page_accepted')) || 1,
                rejected: parseInt(localStorage.getItem('dashboard_page_rejected')) || 1,
                withdrawn: parseInt(localStorage.getItem('dashboard_page_withdrawn')) || 1,
                finished: parseInt(localStorage.getItem('dashboard_page_finished')) || 1
            },
            searchQuery: '',
            statusFilter: '',
            verificationFilter: '',
            sortBy: 'newest'
        };

        // ========== LOAD WITHDRAWN (MENGUNDURKAN DIRI) ==========
        function loadWithdrawnApplicants(page = 1) {
            state.currentPage.withdrawn = page;
            localStorage.setItem('dashboard_page_withdrawn', page);

            console.log('🔍 Loading withdrawn applicants, page:', page);

            $.ajax({
                url: '{{ route('company.dashboard.withdrawn') }}',
                method: 'GET',
                data: {
                    page: page,
                    search: state.searchQuery
                },
                success: function(response) {
                    console.log('✅ Withdrawn response:', response);
                    setTimeout(() => {
                        if (response.success) {
                            renderWithdrawnTable(response);
                            if (response.pagination) {
                                $('#badgeWithdrawn').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading withdrawn:', xhr);

                    showError('Gagal memuat data: ' + (xhr.responseJSON?.message || error));
                }
            });
        }

        function renderWithdrawnTable(response) {
            let html = '';
            const data = response.data || [];

            if (data.length === 0) {
                html = `
            <tr>
                <td colspan="5" class="empty-state">
                    <i class="bi bi-box-arrow-left"></i>
                    <h5 class="text-muted fw-bold mt-2">Tidak ada yang mengundurkan diri</h5>
                    <p class="text-muted">Kandidat yang mengundurkan diri akan muncul di sini</p>
                </td>
            </tr>
        `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const applicationId = app.id || '';

                    const photoUrl = user?.photo ?
                        `/storage/${user.photo}` :
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&size=45&background=667eea&color=fff`;

                    // Truncate reason jika terlalu panjang
                    const reason = app.withdraw_reason || 'Tidak ada alasan';
                    const shortReason = reason.length > 50 ? reason.substring(0, 50) + '...' : reason;

                    html += `
                <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${photoUrl}" class="rounded-circle me-3" 
                                width="45" height="45" style="object-fit: cover; border: 2px solid #e9ecef;">
                            <div>
                                <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                <small class="text-muted">
                                    <i class="bi bi-envelope me-1"></i>${user?.email || '-'}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold">${app.job_posting?.title || '-'}</div>
                        <small class="text-muted">${app.job_posting?.company?.name || '-'}</small>
                    </td>
                    <td>
                        <small class="text-muted">
                            <i class="bi bi-calendar-x me-1"></i>
                            ${formatDate(app.withdrawn_at || app.updated_at)}
                        </small>
                    </td>
                    <td>
                        <span class="text-muted" 
                              data-bs-toggle="tooltip" 
                              data-bs-placement="top" 
                              title="${reason}">
                            ${shortReason}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                data-application-id="${applicationId}"
                                ${!applicationId ? 'disabled' : ''}
                                title="Lihat Detail">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            `;
                });
            }

            $('#tableWithdrawnBody').html(html);
            renderPagination(response.pagination, '#paginationWithdrawn', loadWithdrawnApplicants);

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        // ========== LOAD STATS ==========
        function loadStats() {
            $.ajax({
                url: '{{ route('company.dashboard.stats') }}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#statTotalJobs').text(response.data.total_jobs);
                        $('#statTotalApplicants').text(response.data.total_applicants);
                        $('#statAccepted').text(response.data.accepted);
                        $('#statPending').text(response.data.pending);
                    }
                }
            });
        }

        // ========== LOAD ALL JOBS ==========
        function loadAllJobs(page = 1) {
            state.currentPage.semua = page;
            localStorage.setItem('dashboard_page_semua', page);

            console.log('🔍 Loading jobs with filters:', {
                page: page,
                search: state.searchQuery,
                status: state.statusFilter,
                verification_status: state.verificationFilter, // ✅ Debug log
                sort: state.sortBy
            });

            $.ajax({
                url: '{{ route('company.dashboard.jobs') }}',
                method: 'GET',
                data: {
                    page: page,
                    search: state.searchQuery,
                    status: state.statusFilter,
                    verification_status: state.verificationFilter,
                    sort: state.sortBy
                },
                success: function(response) {
                    setTimeout(() => {
                        if (response.success) {
                            renderJobsTable(response);
                            if (response.pagination) {
                                $('#badgeSemua').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function(xhr) {
                    console.error('❌ Error loading jobs:', xhr);
                    showError('Gagal memuat data');
                }
            });
        }

        function renderJobsTable(response) {
            let html = '';
            const data = response.data || [];

            if (data.length === 0) {
                html = `
            <tr>
                <td colspan="8" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted fw-bold mt-2">Belum ada lowongan</h5>
                    <p class="text-muted">Mulai buat lowongan pekerjaan pertama Anda</p>
                </td>
            </tr>
        `;
            } else {
                data.forEach(job => {
                    const totalApplicants = job.total_applicants || 0;
                    const slot = job.slot || 0;

                    let slotBadgeClass = 'bg-info';
                    if (slot === 0) {
                        slotBadgeClass = 'bg-secondary';
                    } else if (slot > 0 && slot <= 5) {
                        slotBadgeClass = 'bg-warning';
                    } else if (slot > 5) {
                        slotBadgeClass = 'bg-success';
                    }

                    html += `
                <tr class="fade-in clickable-row" data-href="/company/jobs/${job.id}">
                    <td>
                        <div class="fw-semibold text-primary">${job.title}</div>
                        <small class="text-muted"><i class="bi bi-tag me-1"></i>${job.industry?.name || '-'}</small>
                    </td>
                    <td><i class="bi bi-pin-map text-muted me-1"></i>${job.city?.name || '-'}</td>
                    <td><small>${formatDate(job.created_at)}</small></td>
                    <td>
                        <span class="badge bg-primary">
                            <i class="bi bi-person me-1"></i>${totalApplicants}
                        </span>
                    </td>
                    <td>
                        <span class="badge ${slotBadgeClass}" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="Total slot yang tersedia: ${slot}">
                            <i class="bi bi-person-check me-1"></i>
                            ${slot}
                        </span>
                    </td>
                    <td>${getStatusBadge(job.status)}</td>
                    <!-- ✅ TAMBAHKAN KOLOM VERIFIKASI -->
                    <td>${getVerificationBadge(job.verification_status)}</td>
                    <td class="text-center">
                        <a href="/company/jobs/${job.id}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            `;
                });
            }

            $('#tableSemuaBody').html(html);
            renderPagination(response.pagination, '#paginationSemua', loadAllJobs);

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        // ✅ Handle row click untuk SEMUA tabel
        $(document).on('click', '.clickable-row', function(e) {
            // Jangan trigger jika user klik button, badge, atau link
            if ($(e.target).closest('button, .badge, a, .btn-group').length === 0) {

                // ✅ Cek apakah ini row untuk application atau job
                const applicationId = $(this).data('application-id');
                const jobHref = $(this).data('href');

                console.log('🖱️ Row clicked:', {
                    applicationId,
                    jobHref
                });

                // ✅ Jika ada application-id, load candidate detail
                if (applicationId && applicationId !== 'undefined' && applicationId !== '') {
                    loadCandidateDetail(applicationId);
                    return;
                }

                // ✅ Jika ada href, redirect ke halaman job detail
                if (jobHref && jobHref !== 'undefined' && jobHref !== '') {
                    window.location.href = jobHref;
                    return;
                }

                // ✅ Jika tidak ada keduanya, tampilkan error
                console.error('❌ No valid data found:', {
                    applicationId,
                    jobHref
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Data tidak valid. Silakan refresh halaman.',
                    confirmButtonColor: 'var(--primary-blue)'
                });
            }
        });

        // ✅ Handle button detail click untuk tab Applications
        $(document).on('click', '.btn-view-detail', function(e) {
            e.stopPropagation(); // Prevent row click

            const applicationId = $(this).data('application-id');

            console.log('🔘 Button Detail clicked, Application ID:', applicationId);

            // Validasi application ID
            if (!applicationId || applicationId === 'undefined' || applicationId === '') {
                console.error('❌ Invalid application ID:', applicationId);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'ID aplikasi tidak valid. Silakan refresh halaman.',
                    confirmButtonColor: 'var(--primary-blue)'
                });
                return;
            }

            loadCandidateDetail(applicationId);
        });

        // ✅ Handle button "Kirim Email" click
        $(document).on('click', '.send-email-btn', function(e) {
            e.stopPropagation(); // Prevent row click

            const applicationId = $(this).data('id');
            const candidateName = $(this).data('name');
            const candidateEmail = $(this).data('email');
            const position = $(this).data('position');

            console.log('📧 Send Email clicked:', {
                applicationId,
                candidateName,
                candidateEmail,
                position
            });

            // Validasi
            if (!applicationId || !candidateEmail) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Data tidak lengkap untuk mengirim email.',
                    confirmButtonColor: 'var(--primary-blue)'
                });
                return;
            }

            // Open email modal
            openSendEmailModal(applicationId, candidateName, candidateEmail, position);
        });

        // ✅ Function untuk handle row click
        function attachRowClickEvent() {
            $('.clickable-row').off('click').on('click', function(e) {
                // Jangan redirect jika user klik tombol atau link
                if ($(e.target).closest('a, button').length === 0) {
                    window.location.href = $(this).data('href');
                }
            });
        }

        // ========== LOAD INVITED ==========
        function loadInvitedApplicants(page = 1) {
            state.currentPage.pelamar = page;
            localStorage.setItem('dashboard_page_pelamar', page);

            $.ajax({
                url: '{{ route('company.dashboard.invited') }}',
                method: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    setTimeout(() => {
                        if (response.success) {
                            renderInvitedTable(response);
                            if (response.pagination) {
                                $('#badgePelamar').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function() {
                    showError('Gagal memuat data');
                }
            });
        }

        function renderNewApplicantsTable(response) {
            let html = '';
            const data = response.data || [];

            if (data.length === 0) {
                html = `
            <tr>
                <td colspan="5" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted fw-bold mt-2">Tidak ada pelamar baru</h5>
                </td>
            </tr>
        `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const applicationId = app.id || '';

                    html += `
                <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle avatar-circle text-white me-2">
                                ${getInitials(user?.name)}
                            </div>
                            <div>
                                <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                <small class="text-muted">${user?.email || '-'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${app.job_posting?.title || '-'}</td>
                    <td><small>${formatDate(app.created_at)}</small></td>
                    <td>
                        <span class="badge badge-new">
                            <i class="bi bi-star-fill me-1"></i>Baru
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                data-application-id="${applicationId}">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            `;
                });
            }

            $('#tableBaruBody').html(html);
            renderPagination(response.pagination, '#paginationBaru', loadNewApplicants);
        }

        function renderInvitedTable(response) {
            let html = '';
            const data = response.data || [];

            console.log('📊 Invited Table Data:', data); // ✅ Debug log

            if (data.length === 0) {
                html = `
            <tr>
                <td colspan="5" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted fw-bold mt-2">Belum ada undangan</h5>
                </td>
            </tr>
        `;
            } else {
                data.forEach(app => {
                    console.log('🔍 Application Item:', app); // ✅ Debug setiap item

                    const user = app.candidate?.user;
                    const applicationId = app.id || app.application_id ||
                        ''; // ✅ Fallback jika id tidak ada

                    if (!applicationId) {
                        console.error('❌ Application ID not found for:', app);
                    }

                    html += `
                <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle avatar-circle text-white me-2">
                                ${getInitials(user?.name)}
                            </div>
                            <div>
                                <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                <small class="text-muted">${user?.email || '-'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${app.job_posting?.title || '-'}</td>
                    <td><small>${formatDate(app.invited_at)}</small></td>
                    <td>${getApplicationStatusBadge(app.status)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                data-application-id="${applicationId}"
                                ${!applicationId ? 'disabled' : ''}>
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            `;
                });
            }

            $('#tablePelamarBody').html(html);
            renderPagination(response.pagination, '#paginationPelamar', loadInvitedApplicants);
        }

        // ========== LOAD PENDING ==========
        function loadPendingApplicants(page = 1) {
            state.currentPage.pending = page;
            localStorage.setItem('dashboard_page_pending', page);

            $.ajax({
                url: '{{ route('company.dashboard.pending') }}',
                method: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    console.log('✅ Pending response:', response);
                    setTimeout(() => {
                        if (response.success) {
                            renderPendingTable(response);
                            if (response.pagination) {
                                $('#badgePending').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function(xhr) {
                    console.error('❌ Error loading pending:', xhr);

                    showError('Gagal memuat data');
                }
            });
        }

        function renderPendingTable(response) {
            let html = '';
            const data = response.data || [];

            console.log('📊 Rendering pending table with data:', data);

            if (data.length === 0) {
                html = `
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h5 class="text-muted fw-bold mt-2">Tidak ada pelamar menunggu</h5>
                        <p class="text-muted">Pelamar baru akan muncul di sini</p>
                    </td>
                </tr>
            `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const photoUrl = user?.photo ?
                        `/storage/${user.photo}` :
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&size=45&background=667eea&color=fff`;

                    html += `
                    <tr class="fade-in candidate-row clickable-row" data-application-id="${app.id}" style="cursor: pointer;">
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${photoUrl}" class="rounded-circle me-3" 
                                    width="45" height="45" style="object-fit: cover; border: 2px solid #e9ecef;">
                                <div>
                                    <div class="fw-semibold text-primary">${user?.name || 'Unknown'}</div>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope me-1"></i>${user?.email || '-'}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">${app.job_posting?.title || '-'}</div>
                            <small class="text-muted">${app.job_posting?.company?.name || '-'}</small>
                        </td>
                        <td>
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                ${formatDate(app.applied_at)}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-star-fill me-1"></i>${app.candidate?.point || 0} Poin
                            </span>
                        </td>
                    </tr>
                `;
                });
            }

            $('#tablePendingBody').html(html);
            renderPagination(response.pagination, '#paginationPending', loadPendingApplicants);
        }

        // ========== LOAD INTERVIEWED (TELAH INTERVIEW) ==========
        function loadInterviewedApplicants(page = 1) {
            state.currentPage.interviewed = page;
            localStorage.setItem('dashboard_page_interviewed', page);

            console.log('🔍 Loading interviewed applicants, page:', page);

            $.ajax({
                url: '{{ route('company.dashboard.interviewed') }}',
                method: 'GET',
                data: {
                    page: page,
                    search: state.searchQuery
                },
                success: function(response) {
                    console.log('✅ Interviewed response:', response);
                    setTimeout(() => {

                        if (response.success) {
                            renderInterviewedTable(response);
                            if (response.pagination) {
                                $('#badgeInterviewed').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading interviewed:', xhr);

                    showError('Gagal memuat data: ' + (xhr.responseJSON?.message || error));
                }
            });
        }

        function renderInterviewedTable(response) {
            let html = '';
            const data = response.data || [];

            if (data.length === 0) {
                html = `
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="bi bi-camera-video-off"></i>
                            <h5 class="text-muted fw-bold mt-2">Belum ada yang interview</h5>
                            <p class="text-muted">Kandidat yang sudah interview akan muncul di sini</p>
                        </td>
                    </tr>
                `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const applicationId = app.id || '';

                    const photoUrl = user?.photo ?
                        `/storage/${user.photo}` :
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&size=45&background=667eea&color=fff`;

                    html += `
                        <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${photoUrl}" class="rounded-circle me-3" 
                                        width="45" height="45" style="object-fit: cover; border: 2px solid #e9ecef;">
                                    <div>
                                        <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>${user?.email || '-'}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">${app.job_posting?.title || '-'}</div>
                                <small class="text-muted">${app.job_posting?.company?.name || '-'}</small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    ${formatDate(app.updated_at)}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <i class="bi bi-camera-video me-1"></i>Telah Interview
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                        data-application-id="${applicationId}"
                                        ${!applicationId ? 'disabled' : ''}
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#tableInterviewedBody').html(html);
            renderPagination(response.pagination, '#paginationInterviewed', loadInterviewedApplicants);
        }

        // ========== LOAD NOT INTERVIEWED (BELUM INTERVIEW) ==========
        function loadNotInterviewedApplicants(page = 1) {
            state.currentPage.notInterviewed = page;
            localStorage.setItem('dashboard_page_not_interviewed', page);

            console.log('🔍 Loading not interviewed applicants, page:', page);

            $.ajax({
                url: '{{ route('company.dashboard.not-interviewed') }}',
                method: 'GET',
                data: {
                    page: page,
                    search: state.searchQuery
                },
                success: function(response) {
                    console.log('✅ Not interviewed response:', response);
                    setTimeout(() => {

                        if (response.success) {
                            renderNotInterviewedTable(response);
                            if (response.pagination) {
                                $('#badgeNotInterviewed').text(response.pagination.total ||
                                    0);
                            }
                        }
                    }, 300);
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading not interviewed:', xhr);
                    showError('Gagal memuat data: ' + (xhr.responseJSON?.message || error));
                }
            });
        }

        function renderNotInterviewedTable(response) {
            let html = '';
            const data = response.data || [];

            if (data.length === 0) {
                html = `
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="bi bi-camera-video-off"></i>
                            <h5 class="text-muted fw-bold mt-2">Semua sudah interview</h5>
                            <p class="text-muted">Tidak ada kandidat yang belum interview</p>
                        </td>
                    </tr>
                `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const applicationId = app.id || '';

                    const photoUrl = user?.photo ?
                        `/storage/${user.photo}` :
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&size=45&background=667eea&color=fff`;

                    html += `
                        <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${photoUrl}" class="rounded-circle me-3" 
                                        width="45" height="45" style="object-fit: cover; border: 2px solid #e9ecef;">
                                    <div>
                                        <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>${user?.email || '-'}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">${app.job_posting?.title || '-'}</div>
                                <small class="text-muted">${app.job_posting?.company?.name || '-'}</small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    ${formatDate(app.applied_at)}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <i class="bi bi-camera-video-off me-1"></i>Belum Interview
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                        data-application-id="${applicationId}"
                                        ${!applicationId ? 'disabled' : ''}
                                        title="Lihat Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#tableNotInterviewedBody').html(html);
            renderPagination(response.pagination, '#paginationNotInterviewed', loadNotInterviewedApplicants);
        }

        function loadCandidateDetail(applicationId) {
            console.log('🔍 Loading candidate detail for application:', applicationId);

            // ✅ Validasi application ID
            if (!applicationId || applicationId === 'undefined' || applicationId === '') {
                console.error('❌ Invalid application ID:', applicationId);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'ID aplikasi tidak valid.',
                    confirmButtonColor: 'var(--primary-blue)'
                });
                return;
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('candidateDetailModal'));
            modal.show();

            // Show loading
            $('#detailLoading').show();
            $('#detailContent').hide();

            // ✅ Build URL
            const url = `/company/applications/${applicationId}/detail`;

            console.log('📡 Request URL:', url);

            // AJAX request
            $.ajax({
                url: url,
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('✅ Detail loaded:', response);

                    if (response.success) {
                        renderCandidateDetail(response.data);
                        $('#detailLoading').hide();
                        $('#detailContent').show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat detail kandidat',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                        modal.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading detail:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        responseJSON: xhr.responseJSON,
                        error: error
                    });

                    let errorMessage = 'Terjadi kesalahan saat memuat detail kandidat';

                    if (xhr.status === 404) {
                        errorMessage = 'Data tidak ditemukan. Pastikan route sudah benar.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Anda tidak memiliki akses ke data ini.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error: ' + (xhr.responseJSON?.message ||
                            'Internal server error');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: 'var(--primary-blue)'
                    });

                    modal.hide();
                }
            });
        }

        function renderCandidateDetail(data) {
            const app = data;
            const candidate = app.candidate;
            const user = candidate?.user;
            const jobPosting = app.job_posting;

            const photoUrl = user?.photo ?
                `/storage/${user.photo}` :
                `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&size=120&background=667eea&color=fff`;

            const statusBadges = {
                'Applied': '<span class="badge badge-pending">Tahap Seleksi</span>',
                'Reviewed': '<span class="badge badge-pending">Tahap Seleksi</span>',
                'Interview': '<span class="badge badge-pending">Tahap Seleksi</span>',
                'Accepted': '<span class="badge badge-accepted">Diterima</span>',
                'Rejected': '<span class="badge badge-rejected">Ditolak</span>',
                'Withdrawn': '<span class="badge badge-withdrawn">Mengeluarkan Diri</span>',
                'Finished': '<span class="badge badge-finished">Selesai</span>',
                'invited': '<span class="badge badge-invited">Diundang</span>'
            };

            let html = `
            <div class="candidate-profile-header">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="${photoUrl}" class="candidate-photo" alt="${user?.name}">
                    </div>
                    <div class="col-md-7">
                        <h3 class="mb-2">${user?.name || 'Unknown'}</h3>
                        <p class="mb-1"><i class="bi bi-envelope me-2"></i>${user?.email || '-'}</p>
                        <p class="mb-1"><i class="bi bi-phone me-2"></i>${candidate?.phone_number || '-'}</p>
                        <p class="mb-0"><i class="bi bi-star-fill me-2"></i>${candidate?.point || 0} Poin</p>
                    </div>
                    <div class="col-md-3 text-end">
                        <div class="mb-2">Status Saat Ini:</div>
                        ${statusBadges[app.status] || '<span class="badge bg-secondary">Unknown</span>'}
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h5><i class="bi bi-briefcase me-2"></i>Informasi Lamaran</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Posisi:</strong> ${jobPosting?.title || '-'}</p>
                        <p><strong>Tanggal Melamar:</strong> ${formatDate(app.applied_at)}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Gaji:</strong> Rp ${formatNumber(jobPosting?.salary || 0)}</p>
                        <p><strong>Lokasi:</strong> ${jobPosting?.city?.name || '-'}</p>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h5><i class="bi bi-person me-2"></i>Informasi Kandidat</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Jenis Kelamin:</strong> ${candidate?.gender || '-'}</p>
                        <p><strong>Tanggal Lahir:</strong> ${candidate?.birth_date || '-'}</p>
                        <p><strong>Tinggi/Berat:</strong> ${candidate?.height || 0} cm / ${candidate?.weight || 0} kg</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Bahasa Inggris:</strong> ${candidate?.level_english || '-'}</p>
                        <p><strong>Bahasa Mandarin:</strong> ${candidate?.level_mandarin || '-'}</p>
                    </div>
                </div>
                ${candidate?.description ? `
                    <div class="mt-3">
                        <strong>Deskripsi:</strong>
                        <p class="text-muted">${candidate.description}</p>
                    </div>
                ` : ''}
            </div>

            ${candidate?.skills && candidate.skills.length > 0 ? `
                <div class="info-card">
                    <h5><i class="bi bi-tools me-2"></i>Keahlian</h5>
                    <div class="d-flex flex-wrap gap-2">
                        ${candidate.skills.map(skill => `
                            <span class="badge bg-primary">${skill.name}</span>
                        `).join('')}
                    </div>
                </div>
            ` : ''}

            ${candidate?.portofolios && candidate.portofolios.length > 0 ? `
                <div class="info-card">
                    <h5><i class="bi bi-folder me-2"></i>Portfolio</h5>
                    ${candidate.portofolios.map(portfolio => `
                        <div class="mb-2">
                            <strong>${portfolio.caption}</strong><br>
                            <a href="/storage/${portfolio.file}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </div>
                    `).join('')}
                </div>
            ` : ''}

            <div class="status-selector">
                <h5 class="mb-3"><i class="bi bi-arrow-repeat me-2"></i>Update Status Lamaran</h5>
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Pilih Status Baru:</label>
                        <select class="form-select form-select-lg" id="statusSelect">
                            <option value="Applied" ${app.status === 'Applied' ? 'selected' : ''}>Tahap Seleksi (Applied)</option>
                            <option value="Reviewed" ${app.status === 'Reviewed' ? 'selected' : ''}>Tahap Seleksi (Reviewed)</option>
                            <option value="Interview" ${app.status === 'Interview' ? 'selected' : ''}>Tahap Seleksi (Interview)</option>
                            <option value="Accepted" ${app.status === 'Accepted' ? 'selected' : ''}>Diterima</option>
                            <option value="Rejected" ${app.status === 'Rejected' ? 'selected' : ''}>Ditolak</option>
                            <option value="invited" ${app.status === 'invited' ? 'selected' : ''}>Diundang</option>
                            <option value="Finished" ${app.status === 'Finished' ? 'selected' : ''}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-lg w-100" onclick="updateApplicationStatus(${app.id})">
                            <i class="bi bi-check-circle me-2"></i>Update Status
                        </button>
                    </div>
                </div>
            </div>
        `;

            $('#detailContent').html(html);
        }

        window.updateApplicationStatus = function(applicationId) {
            const newStatus = $('#statusSelect').val();

            if (!newStatus) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Status',
                    text: 'Silakan pilih status terlebih dahulu'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda yakin ingin mengubah status menjadi "${newStatus}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ GUNAKAN ROUTE HELPER
                    const url = "{{ route('company.applications.update-status', ':id') }}".replace(
                        ':id', applicationId);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: newStatus
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Status berhasil diupdate'
                                }).then(() => {
                                    $('#candidateDetailModal').modal('hide');
                                    loadPendingApplicants();
                                    loadStats();
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('❌ Error updating status:', xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan'
                            });
                        }
                    });
                }
            });
        }

        // ========== EMAIL FUNCTIONALITY ==========
        $(document).on('click', '.send-email-btn', function() {
            const appId = $(this).data('id');
            const name = $(this).data('name');
            const email = $(this).data('email');
            const position = $(this).data('position');

            // Set modal data
            $('#emailApplicationId').val(appId);
            $('#emailRecipientName').val(name);
            $('#emailRecipientEmail').val(email);
            $('#emailSubject').val(`Terima kasih atas kerjasama pada posisi ${position}`);
            $('#emailMessage').val('');

            // Show modal
            $('#sendEmailModal').modal('show');
        });

        // Template functions
        window.useTemplate = function(type) {
            const name = $('#emailRecipientName').val();
            const position = $('#emailRecipientEmail').val().split('@')[0]; // Simplified

            let subject = '';
            let message = '';

            switch (type) {
                case 'thanks':
                    subject = 'Terima Kasih atas Kerja Sama Anda';
                    message =
                        `Halo ${name},\n\nKami ingin mengucapkan terima kasih yang sebesar-besarnya atas kerja sama dan dedikasi Anda selama ini. Kontribusi Anda sangat berarti bagi perusahaan kami.\n\nKami berharap Anda sukses di masa depan dan semoga kita dapat bekerja sama lagi di kesempatan berikutnya.\n\nSalam hangat,\n${$('#emailRecipientName').closest('.modal').find('.modal-title').text().includes('Kirim') ? 'Tim HR' : 'Perusahaan'}`;
                    break;

                case 'feedback':
                    subject = 'Feedback dan Apresiasi';
                    message =
                        `Halo ${name},\n\nKami ingin memberikan apresiasi atas kinerja dan profesionalisme Anda. Berikut beberapa poin positif yang kami catat:\n\n- Dedikasi dan komitmen tinggi\n- Kemampuan beradaptasi dengan baik\n- Kontribusi positif untuk tim\n\nTerima kasih atas segala usaha Anda. Kami bangga telah bekerja dengan Anda.\n\nSalam,\nTim HR`;
                    break;

                case 'certificate':
                    subject = 'Sertifikat Pengalaman Kerja';
                    message =
                        `Halo ${name},\n\nSesuai permintaan Anda, terlampir sertifikat pengalaman kerja dan dokumen terkait.\n\nJika Anda memerlukan dokumen tambahan atau ada pertanyaan, jangan ragu untuk menghubungi kami.\n\nSemoga sukses untuk karir Anda selanjutnya!\n\nHormat kami,\nTim HR`;
                    break;
            }

            $('#emailSubject').val(subject);
            $('#emailMessage').val(message);
        }

        // Send email
        $('#sendEmailBtn').on('click', function() {
            const appId = $('#emailApplicationId').val();
            const subject = $('#emailSubject').val().trim();
            const message = $('#emailMessage').val().trim();
            const recipientEmail = $('#emailRecipientEmail').val();
            const recipientName = $('#emailRecipientName').val();

            // Validation
            if (!subject) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Subjek Kosong',
                    text: 'Silakan isi subjek email'
                });
                return;
            }

            if (!message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pesan Kosong',
                    text: 'Silakan isi pesan email'
                });
                return;
            }

            // Confirm
            Swal.fire({
                title: 'Konfirmasi Pengiriman',
                html: `Kirim email ke:<br><strong>${recipientName}</strong><br>${recipientEmail}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0d6efd'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengirim Email...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send AJAX
                    $.ajax({
                        url: '{{ route('company.applications.send-email') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            application_id: appId,
                            subject: subject,
                            message: message
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email Terkirim!',
                                text: 'Email berhasil dikirim ke kandidat',
                                confirmButtonColor: '#10b981'
                            });
                            $('#sendEmailModal').modal('hide');
                        },
                        error: function(xhr) {
                            console.error('Error sending email:', xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengirim',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat mengirim email',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
        });

        function formatNumber(num) {
            return parseInt(num || 0).toLocaleString('id-ID');
        }

        // ========== LOAD ACCEPTED ==========
        function loadAcceptedApplicants(page = 1) {
            state.currentPage.accepted = page;
            localStorage.setItem('dashboard_page_accepted', page);

            $.ajax({
                url: '{{ route('company.dashboard.accepted') }}',
                method: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    setTimeout(() => {
                        if (response.success) {
                            renderAcceptedTable(response);
                            if (response.pagination) {
                                $('#badgeDiterima').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function() {
                    showError('Gagal memuat data');
                }
            });
        }

        function renderAcceptedTable(response) {
            let html = '';
            const data = response.data || [];

            console.log('📊 Accepted Table Data:', data); // ✅ Debug log

            if (data.length === 0) {
                html = `
            <tr>
                <td colspan="4" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted fw-bold mt-2">Belum ada yang diterima</h5>
                </td>
            </tr>
        `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const applicationId = app.id || ''; // ✅ Get application ID

                    if (!applicationId) {
                        console.error('❌ Application ID not found for:', app);
                    }

                    html += `
                <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle avatar-circle text-white me-2">
                                ${getInitials(user?.name)}
                            </div>
                            <div>
                                <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                <small class="text-muted">${user?.email || '-'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${app.job_posting?.title || '-'}</td>
                    <td><small>${formatDate(app.updated_at)}</small></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                data-application-id="${applicationId}"
                                ${!applicationId ? 'disabled' : ''}>
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            `;
                });
            }

            $('#tableDiterimaBody').html(html);
            renderPagination(response.pagination, '#paginationDiterima', loadAcceptedApplicants);
        }

        // ========== LOAD REJECTED ==========
        function loadRejectedApplicants(page = 1) {
            state.currentPage.rejected = page;
            localStorage.setItem('dashboard_page_rejected', page);

            $.ajax({
                url: '{{ route('company.dashboard.rejected') }}',
                method: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    setTimeout(() => {
                        if (response.success) {
                            renderRejectedTable(response);
                            if (response.pagination) {
                                $('#badgeDitolak').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function() {
                    showError('Gagal memuat data');
                }
            });
        }

        function renderRejectedTable(response) {
            let html = '';
            const data = response.data || [];

            console.log('📊 Rejected Table Data:', data); // ✅ Debug log

            if (data.length === 0) {
                html = `
            <tr>
                <td colspan="4" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted fw-bold mt-2">Tidak ada yang ditolak</h5>
                </td>
            </tr>
        `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const applicationId = app.id || ''; // ✅ Get application ID

                    if (!applicationId) {
                        console.error('❌ Application ID not found for:', app);
                    }

                    html += `
                <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle avatar-circle text-white me-2">
                                ${getInitials(user?.name)}
                            </div>
                            <div>
                                <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                <small class="text-muted">${user?.email || '-'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${app.job_posting?.title || '-'}</td>
                    <td><small>${formatDate(app.updated_at)}</small></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                data-application-id="${applicationId}"
                                ${!applicationId ? 'disabled' : ''}>
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            `;
                });
            }

            $('#tableDitolakBody').html(html);
            renderPagination(response.pagination, '#paginationDitolak', loadRejectedApplicants);
        }

        // ========== ACCEPT/REJECT ==========
        $(document).on('click', '.accept-btn', function() {
            const appId = $(this).data('id');

            Swal.fire({
                title: 'Terima Pelamar?',
                input: 'textarea',
                inputLabel: 'Pesan (opsional)',
                inputPlaceholder: 'Selamat! Anda diterima...',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Terima',
                confirmButtonColor: '#10b981'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('company/dashboard/accept') }}/${appId}`, {
                        _token: '{{ csrf_token() }}',
                        message: result.value
                    }).done(() => {
                        Swal.fire('Berhasil!', '', 'success');
                        loadStats();
                        loadPendingApplicants(state.currentPage.pending);
                    });
                }
            });
        });

        $(document).on('click', '.reject-btn', function() {
            const appId = $(this).data('id');

            Swal.fire({
                title: 'Tolak Pelamar?',
                input: 'textarea',
                inputLabel: 'Alasan (opsional)',
                inputPlaceholder: 'Maaf...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('company/dashboard/reject') }}/${appId}`, {
                        _token: '{{ csrf_token() }}',
                        message: result.value
                    }).done(() => {
                        Swal.fire('Berhasil!', '', 'success');
                        loadStats();
                        loadPendingApplicants(state.currentPage.pending);
                    });
                }
            });
        });

        // ========== LOAD FINISHED ==========
        function loadFinishedApplicants(page = 1) {
            state.currentPage.finished = page;
            localStorage.setItem('dashboard_page_finished', page);

            console.log('🔍 Loading finished applicants, page:', page);

            $.ajax({
                url: '{{ route('company.dashboard.finished') }}',
                method: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    console.log('✅ Finished response:', response);
                    setTimeout(() => {
                        if (response.success) {
                            renderFinishedTable(response);
                            if (response.pagination) {
                                $('#badgeFinished').text(response.pagination.total || 0);
                            }
                        }
                    }, 300);
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading finished:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        responseJSON: xhr.responseJSON,
                        error: error
                    });
                    showError('Gagal memuat data: ' + (xhr.responseJSON?.message || error));
                }
            });
        }

        function renderFinishedTable(response) {
            let html = '';
            const data = response.data || [];

            console.log('📊 Finished Table Data:', data); // ✅ Debug log

            if (data.length === 0) {
                html = `
            <tr>
                <td colspan="5" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5 class="text-muted fw-bold mt-2">Belum ada yang selesai</h5>
                    <p class="text-muted">Lamaran yang sudah selesai akan muncul di sini</p>
                </td>
            </tr>
        `;
            } else {
                data.forEach(app => {
                    const user = app.candidate?.user;
                    const applicationId = app.id || ''; // ✅ Get application ID

                    if (!applicationId) {
                        console.error('❌ Application ID not found for:', app);
                    }

                    const photoUrl = user?.photo ?
                        `/storage/${user.photo}` :
                        `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&size=45&background=667eea&color=fff`;

                    html += `
                <tr class="fade-in clickable-row" data-application-id="${applicationId}">
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${photoUrl}" class="rounded-circle me-3" 
                                width="45" height="45" style="object-fit: cover; border: 2px solid #e9ecef;">
                            <div>
                                <div class="fw-semibold">${user?.name || 'Unknown'}</div>
                                <small class="text-muted">
                                    <i class="bi bi-envelope me-1"></i>${user?.email || '-'}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold">${app.job_posting?.title || '-'}</div>
                        <small class="text-muted">${app.job_posting?.company?.name || '-'}</small>
                    </td>
                    <td>
                        <small class="text-muted">
                            <i class="bi bi-calendar-check me-1"></i>
                            ${formatDate(app.updated_at)}
                        </small>
                    </td>
                    <td>
                        <span class="badge badge-finished">
                            <i class="bi bi-flag-fill me-1"></i>Selesai
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary btn-view-detail" 
                                    data-application-id="${applicationId}"
                                    ${!applicationId ? 'disabled' : ''}
                                    title="Lihat Detail">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                            <button class="btn btn-sm btn-primary send-email-btn" 
                                    data-id="${applicationId}" 
                                    data-name="${user?.name || 'Unknown'}" 
                                    data-email="${user?.email || ''}"
                                    data-position="${app.job_posting?.title || ''}"
                                    title="Kirim Email">
                                <i class="bi bi-send"></i> Email
                            </button>
                        </div>
                    </td>
                </tr>
            `;
                });
            }

            $('#tableFinishedBody').html(html);
            renderPagination(response.pagination, '#paginationFinished', loadFinishedApplicants);
        }

        // ========== TAB SWITCHING ==========
        $('#mainTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const tab = $(e.target).data('tab');
            state.currentTab = tab;
            localStorage.setItem('dashboard_tab', tab);

            console.log('📑 Tab switched to:', tab);

            switch (tab) {
                case 'semua':
                    loadAllJobs(state.currentPage.semua);
                    break;
                case 'pelamar':
                    loadInvitedApplicants(state.currentPage.pelamar);
                    break;
                case 'pending':
                    loadPendingApplicants(state.currentPage.pending);
                    break;
                case 'interviewed':
                    loadInterviewedApplicants(state.currentPage.interviewed);
                    break;
                case 'notInterviewed': // ✅ Pastikan ini ada
                    loadNotInterviewedApplicants(state.currentPage.notInterviewed);
                    break;
                case 'accepted':
                    loadAcceptedApplicants(state.currentPage.accepted);
                    break;
                case 'rejected':
                    loadRejectedApplicants(state.currentPage.rejected);
                    break;
                case 'withdrawn':
                    loadWithdrawnApplicants(state.currentPage.withdrawn);
                    break;
                case 'finished':
                    loadFinishedApplicants(state.currentPage.finished);
                    break;
                default:
                    console.warn('⚠️ Unknown tab:', tab);
                    loadAllJobs(state.currentPage.semua);
            }
        });

        // ========== SEARCH & FILTER ==========
        $('#searchInput').on('keyup', debounce(function() {
            state.searchQuery = $(this).val();
            console.log('🔍 Search query:', state.searchQuery);
            if (state.currentTab === 'semua') {
                loadAllJobs(1);
            }
        }, 500));

        $('#filterStatus').on('change', function() {
            state.statusFilter = $(this).val();
            console.log('📊 Status filter:', state.statusFilter);
            if (state.currentTab === 'semua') {
                loadAllJobs(1);
            }
        });

        $('#filterVerification').on('change', function() {
            state.verificationFilter = $(this).val();
            console.log('🛡️ Verification filter:', state.verificationFilter); // Debug log
            if (state.currentTab === 'semua') {
                loadAllJobs(1);
            }
        });
        $('#sortBy').on('change', function() {
            state.sortBy = $(this).val();
            console.log('🔄 Sort by:', state.sortBy);
            if (state.currentTab === 'semua') {
                loadAllJobs(1);
            }
        });

        // ========== HELPER FUNCTIONS ==========
        function getVerificationBadge(status) {
            const badges = {
                'Approved': '<span class="badge badge-approved badge-status"><i class="bi bi-shield-check me-1"></i>Approved</span>',
                'Rejected': '<span class="badge badge-rejected-verification badge-status"><i class="bi bi-shield-x me-1"></i>Rejected</span>',
                'Pending': '<span class="badge badge-pending-verification badge-status"><i class="bi bi-clock me-1"></i>Pending</span>'
            };
            return badges[status] || '<span class="badge bg-secondary badge-status">Unknown</span>';
        }

        function renderPagination(pagination, container, callback) {
            if (!pagination || pagination.last_page <= 1) {
                $(container).empty();
                return;
            }

            let html = '<nav class="mt-3"><ul class="pagination justify-content-center">';

            if (pagination.current_page > 1) {
                html +=
                    `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">&laquo;</a></li>`;
            }

            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else if (i === 1 || i === pagination.last_page || Math.abs(i - pagination.current_page) <=
                    2) {
                    html +=
                        `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
                    html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            if (pagination.current_page < pagination.last_page) {
                html +=
                    `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">&raquo;</a></li>`;
            }

            html += '</ul></nav>';
            $(container).html(html);

            $(container).find('a.page-link').on('click', function(e) {
                e.preventDefault();
                callback($(this).data('page'));
            });
        }

        function getStatusBadge(status) {
            const badges = {
                'Open': '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Open</span>',
                'Closed': '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Closed</span>',
                'Draft': '<span class="badge bg-secondary"><i class="bi bi-file me-1"></i>Draft</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">-</span>';
        }

        function getApplicationStatusBadge(status) {
            const badges = {
                'Applied': '<span class="badge badge-pending">Tahap Seleksi</span>',
                'Reviewed': '<span class="badge badge-pending">Tahap Seleksi</span>',
                'Interview': '<span class="badge badge-pending">Tahap Seleksi</span>',
                'Accepted': '<span class="badge badge-accepted">Diterima</span>',
                'Rejected': '<span class="badge badge-rejected">Ditolak</span>',
                'Withdrawn': '<span class="badge badge-withdrawn">Mengeluarkan Diri</span>',
                'Finished': '<span class="badge badge-finished">Selesai</span>',
                'invited': '<span class="badge badge-invited">Diundang</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">-</span>';
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function getInitials(name) {
            if (!name) return '??';
            const parts = name.split(' ');
            return parts.length >= 2 ?
                (parts[0][0] + parts[1][0]).toUpperCase() :
                name.substring(0, 2).toUpperCase();
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                confirmButtonColor: '#dc3545'
            });
        }

        // ========== RESTORE TAB & PAGE FROM LOCALSTORAGE ==========
        const savedTab = localStorage.getItem('dashboard_tab');
        if (savedTab && savedTab !== 'semua') {
            $(`#mainTabs a[data-tab="${savedTab}"]`).tab('show');
        }

        // ========== INITIAL LOAD ==========
        loadStats();
        loadAllJobs(state.currentPage.semua);

        console.log('✅ Dashboard ready');
    });
</script>
