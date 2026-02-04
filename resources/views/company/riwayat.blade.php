    @extends('layouts.main')

    @section('title', 'Riwayat Perusahaan')

    <style>
        .select2-container--default .select2-selection--multiple {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.5rem;
            min-height: 45px;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            border: none;
            cursor: not-allowed;
        }

        .btn-dark {
            background: linear-gradient(135deg, #374151, #1f2937);
            color: white;
            border: none;
            cursor: not-allowed;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(20, 72, 155, 0.1);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 8px;
            font-weight: bold;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fecaca;
        }

        .select2-dropdown {
            border: 2px solid var(--primary-blue);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-blue);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        :root {
            --primary-blue: #14489b;
            --secondary-blue: #244770;
            --dark-blue: #1e3992;
            --light-blue: #dbeafe;
            --bg-blue: #eff6ff;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(20, 72, 155, 0.3);
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .filter-bar {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 1.5rem;
            border-bottom: 3px solid var(--light-blue);
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-select {
            min-width: 200px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.3s;
        }

        .filter-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(20, 72, 155, 0.1);
        }

        .nav-tabs {
            border-bottom: 3px solid var(--light-blue);
            padding: 0 1.5rem;
            background: white;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6b7280;
            font-weight: 600;
            padding: 1.25rem 2rem;
            transition: all 0.3s;
            position: relative;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-blue);
            background: var(--bg-blue);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-blue);
            background: transparent;
        }

        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-blue);
        }

        .tab-badge {
            background: #e5e7eb;
            color: #6b7280;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-left: 0.5rem;
        }

        .nav-tabs .nav-link.active .tab-badge {
            background: var(--primary-blue);
            color: white;
        }

        .tab-content {
            padding: 2rem;
            min-height: 500px;
        }

        .history-item {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .history-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            transform: scaleY(0);
            transition: transform 0.3s;
        }

        .history-item:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 8px 25px rgba(20, 72, 155, 0.15);
            transform: translateX(5px);
        }

        .history-item:hover::before {
            transform: scaleY(1);
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .candidate-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .candidate-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(20, 72, 155, 0.3);
        }

        .candidate-details h5 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }

        .candidate-email {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .history-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 1rem 0;
            padding: 1rem;
            background: var(--bg-blue);
            border-radius: 10px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4b5563;
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--primary-blue);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-applied {
            background: linear-gradient(135deg, #6b7280, #4b5563);
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

        .badge-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .badge-reviewed {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .badge-interview {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .rating-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 10px;
            font-weight: 700;
            color: #92400e;
        }

        .rating-stars {
            color: #f59e0b;
            font-size: 1.25rem;
        }

        .review-box {
            background: #f8f9fa;
            border-left: 4px solid var(--primary-blue);
            padding: 1.25rem;
            border-radius: 10px;
            margin-top: 1rem;
        }

        .review-text {
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        .feedback-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .feedback-tag {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #e5e7eb;
        }

        .btn-rate {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-rate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-rated {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            cursor: default;
        }

        .btn-rated:hover {
            transform: none;
        }

        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .page-link {
            border: 2px solid #e5e7eb;
            color: var(--primary-blue);
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            margin: 0 0.25rem;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .page-link:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
        }

        .page-item.active .page-link {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h4 {
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #e5e7eb;
            border-top-color: var(--primary-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.75rem;
            }

            .nav-tabs .nav-link {
                padding: 1rem;
                font-size: 0.9rem;
            }

            .history-item {
                padding: 1rem;
            }

            .candidate-avatar {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }

        .history-item {
            animation: fadeInUp 0.5s ease-out;
        }

        .history-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .history-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .history-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .history-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .history-item:nth-child(5) {
            animation-delay: 0.25s;
        }
    </style>

    @section('content')
        <div class="container py-4">
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title">
                        <i class="bi bi-clock-history me-3"></i>
                        Riwayat Perusahaan
                    </h1>
                    <p class="page-subtitle">
                        Kelola riwayat aplikasi, rating, dan feedback dari kandidat
                    </p>
                </div>
            </div>


            <div class="main-card">
                <div class="filter-bar">
                    <div class="flex-grow-1">
                        <label class="form-label mb-2 fw-bold">
                            <i class="bi bi-funnel me-2"></i>Filter Status
                        </label>
                        <select class="form-select filter-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="Applied" {{ $statusFilter == 'Applied' ? 'selected' : '' }}>Applied</option>
                            <option value="Reviewed" {{ $statusFilter == 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="Interview" {{ $statusFilter == 'Interview' ? 'selected' : '' }}>Interview
                            </option>
                            <option value="Accepted" {{ $statusFilter == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="Rejected" {{ $statusFilter == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <ul class="nav nav-tabs" id="historyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications"
                            type="button" role="tab">
                            <i class="bi bi-file-earmark-text me-2"></i>Aplikasi
                            <span class="tab-badge">{{ $applications->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ratings-tab" data-bs-toggle="tab" data-bs-target="#ratings"
                            type="button" role="tab">
                            <i class="bi bi-star me-2"></i>Rating
                            <span class="tab-badge">{{ $ratingsFromCandidates->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                            type="button" role="tab">
                            <i class="bi bi-chat-quote me-2"></i>Review
                            <span class="tab-badge">{{ $reviewsFromCandidates->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#report"
                            type="button" role="tab">
                            <i class="bi bi-flag me-2"></i>Report
                            <span class="tab-badge">{{ $reviewsToReport->total() }}</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="feedback-tab" data-bs-toggle="tab" data-bs-target="#feedback"
                            type="button" role="tab">
                            <i class="bi bi-hand-thumbs-up me-2"></i>Feedback
                            <span class="tab-badge">{{ $feedbackGivenByCompany->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted"
                            type="button" role="tab">
                            <i class="bi bi-check-circle me-2"></i>Diterima
                            <span class="tab-badge">{{ $acceptedApplications->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected"
                            type="button" role="tab">
                            <i class="bi bi-x-circle me-2"></i>Ditolak
                            <span class="tab-badge">{{ $rejectedApplications->total() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="historyTabsContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        @forelse($allItems as $item)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($item->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $item->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $item->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $statusClass = match ($item->status) {
                                                'Applied' => 'badge-applied',
                                                'Reviewed' => 'badge-reviewed',
                                                'Interview' => 'badge-interview',
                                                'Accepted' => 'badge-accepted',
                                                'Rejected' => 'badge-rejected',
                                                default => 'badge-pending',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                            {{ $item->status }}
                                        </span>
                                    </div>
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $item->jobVacancy->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Melamar:</strong>
                                        <span>{{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                    @if ($item->rating_candidates)
                                        <div class="meta-item">
                                            <i class="bi bi-star-fill"></i>
                                            <strong>Rating:</strong>
                                            <span>{{ $item->rating_candidates }}/5</span>
                                        </div>
                                    @endif
                                </div>

                                @if ($item->review_candidate)
                                    <div class="review-box">
                                        <h6 class="mb-2">
                                            <i class="bi bi-chat-quote me-2"></i>
                                            Review Anda:
                                        </h6>
                                        <p class="review-text">{{ $item->review_candidate }}</p>
                                    </div>
                                @endif

                                @if ($item->feedbacks && $item->feedbacks->count() > 0)
                                    <div class="feedback-tags">
                                        @foreach ($item->feedbacks as $feedback)
                                            <span class="feedback-tag">
                                                <i class="bi bi-tag-fill"></i>
                                                {{ $feedback->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="action-buttons">
                                    @if (!$item->rating_candidates && in_array($item->status, ['Accepted', 'Rejected']))
                                        <button class="btn btn-rate btn-rate-candidate"
                                            data-application-id="{{ $item->id }}"
                                            data-candidate-name="{{ $item->candidate->name ?? 'Kandidat' }}">
                                            <i class="bi bi-star"></i>
                                            Beri Rating & Review
                                        </button>
                                    @elseif($item->rating_candidates)
                                        <button class="btn btn-rate btn-rated" disabled>
                                            <i class="bi bi-check-circle"></i>
                                            Sudah Diberi Rating
                                        </button>
                                    @endif

                                    @if ($item->candidate && $item->candidate->cv_path)
                                        <a href="{{ Storage::url($item->candidate->cv_path) }}"
                                            class="btn btn-outline-primary" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>
                                            Lihat CV
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h4>Belum Ada Riwayat</h4>
                                <p>Riwayat akan muncul setelah kandidat melamar</p>
                            </div>
                        @endforelse

                        {{ $allItems->appends(['status' => $statusFilter])->links() }}
                    </div>

                    <div class="tab-pane fade" id="applications" role="tabpanel">
                        @forelse($applications as $application)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($application->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $application->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $application->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $statusClass = match ($application->status) {
                                                'Applied' => 'badge-applied',
                                                'Reviewed' => 'badge-reviewed',
                                                'Interview' => 'badge-interview',
                                                'Accepted' => 'badge-accepted',
                                                'Rejected' => 'badge-rejected',
                                                default => 'badge-pending',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                            {{ $application->status }}
                                        </span>
                                    </div>
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $application->jobVacancy->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Melamar:</strong>
                                        <span>{{ $application->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="action-buttons">
                                    @if (!$application->rating_candidates && in_array($application->status, ['Accepted', 'Rejected']))
                                        <button class="btn btn-rate btn-rate-candidate"
                                            data-application-id="{{ $application->id }}"
                                            data-candidate-name="{{ $application->candidate->name ?? 'Kandidat' }}">
                                            <i class="bi bi-star"></i>
                                            Beri Rating & Review
                                        </button>
                                    @elseif($application->rating_candidates)
                                        <button class="btn btn-rate btn-rated" disabled>
                                            <i class="bi bi-check-circle"></i>
                                            Sudah Diberi Rating
                                        </button>
                                    @endif

                                    @if ($application->candidate && $application->candidate->cv_path)
                                        <a href="{{ Storage::url($application->candidate->cv_path) }}"
                                            class="btn btn-outline-primary" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>
                                            Lihat CV
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h4>Belum Ada Aplikasi</h4>
                                <p>Aplikasi akan muncul di sini</p>
                            </div>
                        @endforelse

                        {{ $applications->appends(['status' => $statusFilter])->links() }}
                    </div>

                    <div class="tab-pane fade" id="ratings" role="tabpanel">
                        @forelse($ratingsFromCandidates as $rating)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($rating->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $rating->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $rating->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="rating-display">
                                        <span class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating->rating_candidates)
                                                    <i class="bi bi-star-fill"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span>{{ $rating->rating_candidates }}/5</span>
                                    </div>
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $rating->jobVacancy->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Rating:</strong>
                                        <span>{{ $rating->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                @if ($rating->review_candidate)
                                    <div class="review-box">
                                        <h6 class="mb-2">
                                            <i class="bi bi-chat-quote me-2"></i>
                                            Review Anda:
                                        </h6>
                                        <p class="review-text">{{ $rating->review_candidate }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-star"></i>
                                <h4>Belum Ada Rating</h4>
                                <p>Rating dari kandidat akan muncul di sini</p>
                            </div>
                        @endforelse

                        {{ $ratingsFromCandidates->appends(['status' => $statusFilter])->links() }}
                    </div>

                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        @forelse($reviewsFromCandidates as $review)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($review->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $review->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $review->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                    @if ($review->rating_candidates)
                                        <div class="rating-display">
                                            <span class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating_candidates)
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                            <span>{{ $review->rating_candidates }}/5</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $review->jobVacancy->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Review:</strong>
                                        <span>{{ $review->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="review-box">
                                    <h6 class="mb-2">
                                        <i class="bi bi-chat-quote me-2"></i>
                                        Review Anda:
                                    </h6>
                                    <p class="review-text">{{ $review->review_candidate }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-chat-quote"></i>
                                <h4>Belum Ada Review</h4>
                                <p>Review dari kandidat akan muncul di sini</p>
                            </div>
                        @endforelse

                        {{ $reviewsFromCandidates->appends(['status' => $statusFilter])->links() }}
                    </div>

                    <div class="tab-pane fade" id="feedback" role="tabpanel">
                        @forelse($feedbackGivenByCompany as $feedback)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($feedback->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $feedback->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $feedback->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $feedback->jobVacancy->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Feedback:</strong>
                                        <span>{{ $feedback->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                @if ($feedback->feedbacks && $feedback->feedbacks->count() > 0)
                                    <div class="feedback-tags">
                                        @foreach ($feedback->feedbacks as $fb)
                                            <span class="feedback-tag">
                                                <i class="bi bi-tag-fill"></i>
                                                {{ $fb->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-hand-thumbs-up"></i>
                                <h4>Belum Ada Feedback</h4>
                                <p>Feedback yang Anda berikan akan muncul di sini</p>
                            </div>
                        @endforelse

                        {{ $feedbackGivenByCompany->appends(['status' => $statusFilter])->links() }}
                    </div>

                    <div class="tab-pane fade" id="report" role="tabpanel">
                        @forelse($reviewsToReport as $review)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($review->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $review->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $review->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                    @if ($review->rating_company)
                                        <div class="rating-display">
                                            <span class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating_company)
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                            <span>{{ $review->rating_company }}/5</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $review->jobPosting->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Review:</strong>
                                        <span>{{ $review->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="review-box">
                                    <h6 class="mb-2">
                                        <i class="bi bi-chat-quote me-2"></i>
                                        Review dari Kandidat:
                                    </h6>
                                    <p class="review-text">{{ $review->review_company }}</p>
                                </div>

                                <div class="action-buttons">
                                    @php
                                        $isReported = $review->reports->where('user_id', Auth::id())->count() > 0;
                                        $isBlocked = in_array($review->candidate->user_id, $blacklistedUsers);
                                    @endphp

                                    @if ($isReported)
                                        <button class="btn btn-secondary" disabled>
                                            <i class="bi bi-flag-fill me-2"></i>
                                            Sudah Dilaporkan
                                        </button>
                                    @else
                                        <button class="btn btn-warning btn-report-review"
                                            data-application-id="{{ $review->id }}"
                                            data-candidate-name="{{ $review->candidate->name ?? 'Kandidat' }}">
                                            <i class="bi bi-flag me-2"></i>
                                            Laporkan Review
                                        </button>
                                    @endif

                                    @if ($isBlocked)
                                        <button class="btn btn-dark" disabled>
                                            <i class="bi bi-slash-circle-fill me-2"></i>
                                            Sudah Diblokir
                                        </button>
                                    @else
                                        <button class="btn btn-danger btn-block-user"
                                            data-application-id="{{ $review->id }}"
                                            data-candidate-name="{{ $review->candidate->name ?? 'Kandidat' }}"
                                            data-candidate-user-id="{{ $review->candidate->user_id }}">
                                            <i class="bi bi-slash-circle me-2"></i>
                                            Blokir Pengguna
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-flag"></i>
                                <h4>Belum Ada Review untuk Dilaporkan</h4>
                                <p>Review dari kandidat akan muncul di sini</p>
                            </div>
                        @endforelse

                        {{ $reviewsToReport->appends(['status' => $statusFilter])->links() }}
                    </div>

                    <div class="tab-pane fade" id="accepted" role="tabpanel">
                        @forelse($acceptedApplications as $accepted)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($accepted->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $accepted->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $accepted->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge badge-accepted">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Diterima
                                        </span>
                                    </div>
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $accepted->jobVacancy->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Diterima:</strong>
                                        <span>{{ $accepted->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="action-buttons">
                                    @if (!$accepted->rating_candidates)
                                        <button class="btn btn-rate btn-rate-candidate"
                                            data-application-id="{{ $accepted->id }}"
                                            data-candidate-name="{{ $accepted->candidate->name ?? 'Kandidat' }}">
                                            <i class="bi bi-star"></i>
                                            Beri Rating & Review
                                        </button>
                                    @else
                                        <button class="btn btn-rate btn-rated" disabled>
                                            <i class="bi bi-check-circle"></i>
                                            Sudah Diberi Rating
                                        </button>
                                    @endif

                                    @if ($accepted->candidate && $accepted->candidate->cv_path)
                                        <a href="{{ Storage::url($accepted->candidate->cv_path) }}"
                                            class="btn btn-outline-primary" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>
                                            Lihat CV
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-check-circle"></i>
                                <h4>Belum Ada Kandidat Diterima</h4>
                                <p>Kandidat yang diterima akan muncul di sini</p>
                            </div>
                        @endforelse

                        {{ $acceptedApplications->appends(['status' => $statusFilter])->links() }}
                    </div>

                    <div class="tab-pane fade" id="rejected" role="tabpanel">
                        @forelse($rejectedApplications as $rejected)
                            <div class="history-item">
                                <div class="history-header">
                                    <div class="candidate-info">
                                        <div class="candidate-avatar">
                                            {{ strtoupper(substr($rejected->candidate->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="candidate-details">
                                            <h5>{{ $rejected->candidate->name ?? 'Nama Tidak Tersedia' }}</h5>
                                            <p class="candidate-email mb-0">
                                                <i class="bi bi-envelope me-2"></i>
                                                {{ $rejected->candidate->email ?? 'Email Tidak Tersedia' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge badge-rejected">
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                            Ditolak
                                        </span>
                                    </div>
                                </div>

                                <div class="history-meta">
                                    <div class="meta-item">
                                        <i class="bi bi-briefcase-fill"></i>
                                        <strong>Posisi:</strong>
                                        <span>{{ $rejected->jobVacancy->job_title ?? 'Tidak Tersedia' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>Tanggal Ditolak:</strong>
                                        <span>{{ $rejected->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="action-buttons">
                                    @if (!$rejected->rating_candidates)
                                        <button class="btn btn-rate btn-rate-candidate"
                                            data-application-id="{{ $rejected->id }}"
                                            data-candidate-name="{{ $rejected->candidate->name ?? 'Kandidat' }}">
                                            <i class="bi bi-star"></i>
                                            Beri Rating & Review
                                        </button>
                                    @else
                                        <button class="btn btn-rate btn-rated" disabled>
                                            <i class="bi bi-check-circle"></i>
                                            Sudah Diberi Rating
                                        </button>
                                    @endif

                                    @if ($rejected->candidate && $rejected->candidate->cv_path)
                                        <a href="{{ Storage::url($rejected->candidate->cv_path) }}"
                                            class="btn btn-outline-primary" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>
                                            Lihat CV
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-x-circle"></i>
                                <h4>Belum Ada Kandidat Ditolak</h4>
                                <p>Kandidat yang ditolak akan muncul di sini</p>
                            </div>
                        @endforelse

                        {{ $rejectedApplications->appends(['status' => $statusFilter])->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner"></div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.btn-report-review').forEach(button => {
                    button.addEventListener('click', async function() {
                        const applicationId = this.dataset.applicationId;
                        const candidateName = this.dataset.candidateName;

                        const {
                            value: reason
                        } = await Swal.fire({
                            title: 'Laporkan Review',
                            html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kandidat:</label>
                            <p class="mb-0">${candidateName}</p>
                        </div>
                        <div class="mb-3">
                            <label for="reportReason" class="form-label fw-bold">
                                Alasan Pelaporan <span class="text-danger">*</span>
                            </label>
                            <textarea id="reportReason" class="form-control" rows="4"
                                    placeholder="Jelaskan mengapa Anda melaporkan review ini..."
                                    maxlength="500"></textarea>
                            <small class="text-muted">Maksimal 500 karakter</small>
                        </div>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Laporan akan ditinjau oleh tim kami.
                        </div>
                    </div>
                `,
                            width: '600px',
                            showCancelButton: true,
                            confirmButtonText: '<i class="bi bi-flag me-2"></i>Kirim Laporan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#f59e0b',
                            cancelButtonColor: '#6c757d',
                            preConfirm: () => {
                                const reason = document.getElementById('reportReason')
                                    .value.trim();

                                if (!reason) {
                                    Swal.showValidationMessage(
                                        'Alasan pelaporan wajib diisi!');
                                    return false;
                                }

                                if (reason.length < 10) {
                                    Swal.showValidationMessage(
                                        'Alasan minimal 10 karakter!');
                                    return false;
                                }

                                return reason;
                            }
                        });

                        if (!reason) return;

                        Swal.fire({
                            title: 'Mengirim Laporan...',
                            html: '<div class="spinner-border text-warning" style="width: 3rem; height: 3rem;"></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });

                        try {
                            const response = await fetch(
                                `{{ route('company.riwayat.report', '') }}/${applicationId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        reason
                                    })
                                });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                location.reload();
                            } else {
                                throw new Error(data.message || 'Gagal mengirim laporan');
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message,
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                });

                document.querySelectorAll('.btn-block-user').forEach(button => {
                    button.addEventListener('click', async function() {
                        const applicationId = this.dataset.applicationId;
                        const candidateName = this.dataset.candidateName;

                        const {
                            value: reason
                        } = await Swal.fire({
                            title: 'Blokir Pengguna',
                            html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kandidat:</label>
                            <p class="mb-0 text-danger fw-bold">${candidateName}</p>
                        </div>
                        <div class="mb-3">
                            <label for="blockReason" class="form-label fw-bold">
                                Alasan Pemblokiran <span class="text-danger">*</span>
                            </label>
                            <textarea id="blockReason" class="form-control" rows="4"
                                    placeholder="Jelaskan mengapa Anda memblokir pengguna ini..."
                                    maxlength="500"></textarea>
                            <small class="text-muted">Maksimal 500 karakter</small>
                        </div>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Peringatan:</strong> Setelah diblokir, pengguna ini tidak akan bisa melamar ke lowongan Anda lagi.
                        </div>
                    </div>
                `,
                            width: '600px',
                            showCancelButton: true,
                            confirmButtonText: '<i class="bi bi-slash-circle me-2"></i>Blokir Sekarang',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            preConfirm: () => {
                                const reason = document.getElementById('blockReason')
                                    .value.trim();

                                if (!reason) {
                                    Swal.showValidationMessage(
                                        'Alasan pemblokiran wajib diisi!');
                                    return false;
                                }

                                if (reason.length < 10) {
                                    Swal.showValidationMessage(
                                        'Alasan minimal 10 karakter!');
                                    return false;
                                }

                                return reason;
                            }
                        });

                        if (!reason) return;

                        const confirm = await Swal.fire({
                            title: 'Yakin Blokir Pengguna?',
                            text: `${candidateName} tidak akan bisa melamar ke lowongan Anda lagi!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Blokir!',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d'
                        });

                        if (!confirm.isConfirmed) return;

                        Swal.fire({
                            title: 'Memblokir Pengguna...',
                            html: '<div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });

                        try {
                            const response = await fetch(
                                `{{ route('company.riwayat.block', '') }}/${applicationId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        reason
                                    })
                                });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                location.reload();
                            } else {
                                throw new Error(data.message || 'Gagal memblokir pengguna');
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message,
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                });

                const tabButtons = document.querySelectorAll('#historyTabs button[data-bs-toggle="tab"]');
                const savedTab = localStorage.getItem('companyHistoryActiveTab');

                if (savedTab) {
                    const savedTabButton = document.querySelector(`#historyTabs button[data-bs-target="${savedTab}"]`);
                    if (savedTabButton) {
                        const tab = new bootstrap.Tab(savedTabButton);
                        tab.show();
                    }
                }

                tabButtons.forEach(button => {
                    button.addEventListener('shown.bs.tab', function(e) {
                        const target = e.target.getAttribute('data-bs-target');
                        localStorage.setItem('companyHistoryActiveTab', target);
                    });
                });

                const statusFilter = document.getElementById('statusFilter');
                const clearFilterBtn = document.getElementById('clearFilter');

                statusFilter.addEventListener('change', function() {
                    const status = this.value;
                    const url = new URL(window.location.href);

                    if (status) {
                        url.searchParams.set('status', status);
                    } else {
                        url.searchParams.delete('status');
                    }

                    showLoading();
                    window.location.href = url.toString();
                });

                clearFilterBtn.addEventListener('click', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('status');
                    showLoading();
                    window.location.href = url.toString();
                });

                document.querySelectorAll('.btn-rate-candidate').forEach(button => {
                    button.addEventListener('click', async function() {
                        const applicationId = this.dataset.applicationId;
                        const candidateName = this.dataset.candidateName;

                        const {
                            value: formValues
                        } = await Swal.fire({
                            title: 'Beri Rating & Review',
                            html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kandidat:</label>
                            <p class="mb-0">${candidateName}</p>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label fw-bold">
                                Rating <span class="text-danger">*</span>
                            </label>
                            <div class="star-rating" id="starRating">
                                ${[1,2,3,4,5].map(star => `
                                                                                                        <i class="bi bi-star star-icon" data-rating="${star}" style="font-size: 2rem; cursor: pointer; color: #d1d5db;"></i>
                                                                                                    `).join('')}
                            </div>
                            <input type="hidden" id="ratingValue" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label fw-bold">Review</label>
                            <textarea id="review" class="form-control" rows="4" placeholder="Tulis review Anda..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="feedbackSelect" class="form-label fw-bold">Feedback (Opsional)</label>
                            <select id="feedbackSelect" class="form-control" multiple="multiple" style="width: 100%;">
                                @foreach ($feedbacks as $feedback)
                                    <option value="{{ $feedback->id }}">{{ $feedback->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih satu atau lebih feedback</small>
                        </div>
                    </div>
                `,
                            width: '600px',
                            showCancelButton: true,
                            confirmButtonText: '<i class="bi bi-save me-2"></i>Simpan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#6c757d',
                            didOpen: () => {
                                $('#feedbackSelect').select2({
                                    placeholder: 'Pilih feedback...',
                                    allowClear: true,
                                    closeOnSelect: false,
                                    dropdownParent: $('.swal2-popup'),
                                    language: {
                                        noResults: function() {
                                            return "Tidak ada hasil ditemukan";
                                        },
                                        searching: function() {
                                            return "Mencari...";
                                        }
                                    }
                                });

                                const stars = document.querySelectorAll('.star-icon');
                                const ratingInput = document.getElementById(
                                    'ratingValue');

                                stars.forEach(star => {
                                    star.addEventListener('click', function() {
                                        const rating = this.dataset
                                            .rating;
                                        ratingInput.value = rating;

                                        stars.forEach((s, index) => {
                                            if (index <
                                                rating) {
                                                s.classList
                                                    .remove(
                                                        'bi-star'
                                                    );
                                                s.classList.add(
                                                    'bi-star-fill'
                                                );
                                                s.style.color =
                                                    '#f59e0b';
                                            } else {
                                                s.classList
                                                    .remove(
                                                        'bi-star-fill'
                                                    );
                                                s.classList.add(
                                                    'bi-star'
                                                );
                                                s.style.color =
                                                    '#d1d5db';
                                            }
                                        });
                                    });

                                    star.addEventListener('mouseenter',
                                        function() {
                                            const rating = this.dataset
                                                .rating;
                                            stars.forEach((s, index) => {
                                                if (index <
                                                    rating) {
                                                    s.style.color =
                                                        '#f59e0b';
                                                }
                                            });
                                        });

                                    star.addEventListener('mouseleave',
                                        function() {
                                            const currentRating =
                                                ratingInput.value;
                                            stars.forEach((s, index) => {
                                                if (index >=
                                                    currentRating) {
                                                    s.style.color =
                                                        '#d1d5db';
                                                }
                                            });
                                        });
                                });
                            },
                            didClose: () => {
                                if ($('#feedbackSelect').data('select2')) {
                                    $('#feedbackSelect').select2('destroy');
                                }
                            },
                            preConfirm: () => {
                                const rating = document.getElementById('ratingValue')
                                    .value;
                                const review = document.getElementById('review').value
                                    .trim();
                                const feedbacks = $('#feedbackSelect').val() || [];

                                if (rating == 0) {
                                    Swal.showValidationMessage('Rating wajib diisi!');
                                    return false;
                                }

                                return {
                                    rating,
                                    review,
                                    feedbacks
                                };
                            }
                        });

                        if (!formValues) return;

                        Swal.fire({
                            title: 'Menyimpan...',
                            html: '<div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>',
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });

                        try {
                            const response = await fetch(
                                `{{ route('company.riwayat.rate', '') }}/${applicationId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        rating_candidates: formValues.rating,
                                        review_candidate: formValues.review,
                                        feedbacks: formValues.feedbacks
                                    })
                                });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                location.reload();
                            } else {
                                throw new Error(data.message || 'Gagal menyimpan rating');
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message,
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                });

                function showLoading() {
                    document.getElementById('loadingOverlay').classList.add('active');
                }

                function hideLoading() {
                    document.getElementById('loadingOverlay').classList.remove('active');
                }
            });
        </script>
    @endsection
