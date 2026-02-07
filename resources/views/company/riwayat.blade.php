@extends('layouts.main')

@section('content')

    <style>
        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 24px 24px;
            box-shadow: 0 4px 20px rgba(20, 72, 155, 0.15);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 400;
        }

        /* Main Card */
        .main-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .filter-select {
            max-width: 250px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: #fff;
        }

        /* Tabs */
        .nav-tabs {
            border-bottom: 1px solid #e2e8f0;
            background: white;
            padding: 0 1rem;
            gap: 0.25rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 500;
            padding: 1rem 1.25rem;
            transition: all 0.2s;
            position: relative;
            font-size: 0.9rem;
            border-radius: 0;
            background: transparent;
        }

        .tab-badge {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .nav-tabs .nav-link.active .tab-badge {
            background: var(--primary-blue);
            color: white;
        }

        /* Tab Content */
        .tab-content {
            padding: 1.5rem;
            min-height: 400px;
        }

        /* History Item */
        .history-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }

        /* History Header */
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 1rem;
        }

        .candidate-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .candidate-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .candidate-details h5 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
        }

        .candidate-email {
            color: #64748b;
            font-size: 0.875rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* History Meta */
        .history-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            font-size: 0.875rem;
        }

        .meta-item i {
            color: var(--primary-blue);
            font-size: 0.875rem;
        }

        .meta-item strong {
            font-weight: 500;
        }

        /* Badges */
        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.813rem;
            border: none;
        }

        .badge-finished {
            background: #d4edda;
            color: #155724;
        }

        .badge-accepted {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-invited {
            background: #e7d4f8;
            color: #6610f2;
        }

        .badge-withdrawn {
            background: #d6d8db;
            color: #383d41;
        }

        .badge-selection {
            background: #cfe2ff;
            color: #084298;
        }

        /* Rating Display */
        .rating-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #fef3c7;
            border-radius: 8px;
            font-weight: 600;
            color: #92400e;
            font-size: 0.875rem;
        }

        .rating-stars {
            color: #f59e0b;
            font-size: 1rem;
        }

        /* Review Box */
        .review-box {
            background: #f8fafc;
            border-left: 3px solid var(--primary-blue);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .review-box h6 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .review-text {
            color: #64748b;
            line-height: 1.6;
            margin: 0;
            font-size: 0.875rem;
        }

        /* Feedback Tags */
        .feedback-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .feedback-tag {
            background: #eff6ff;
            color: #1e40af;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.813rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-rate {
            background: var(--primary-blue);
            color: white;
        }

        .btn-rated {
            background: #10b981;
            color: white;
            cursor: default;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #64748b;
            cursor: not-allowed;
        }

        .btn-dark {
            background: #475569;
            color: white;
            cursor: not-allowed;
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem;
            min-height: 42px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(20, 72, 155, 0.1);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 0.25rem 0.625rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.813rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 0.5rem;
            font-weight: 600;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fecaca;
        }

        .select2-dropdown {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-blue);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.5rem;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1.5rem;
            gap: 0.25rem;
        }

        .page-link {
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-weight: 500;
            padding: 0.5rem 0.875rem;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .page-item.active .page-link {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
        }

        .page-link:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.4;
        }

        .empty-state h4 {
            color: #64748b;
            margin-bottom: 0.5rem;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .empty-state p {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }

            .nav-tabs .nav-link {
                padding: 0.875rem 1rem;
                font-size: 0.85rem;
            }

            .tab-badge {
                font-size: 0.7rem;
                padding: 0.125rem 0.375rem;
            }

            .history-item {
                padding: 1.25rem;
            }

            .candidate-avatar {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .candidate-details h5 {
                font-size: 0.938rem;
            }

            .history-meta {
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                justify-content: center;
            }

            .filter-select {
                max-width: 100%;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }

        .history-item {
            animation: fadeInUp 0.4s ease-out;
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
                    <label class="form-label mb-2 fw-semibold" style="font-size: 0.875rem; color: #475569;">
                        <i class="bi bi-funnel me-2"></i>Filter Status
                    </label>
                    <select class="form-select filter-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ $statusFilter == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Selection" {{ $statusFilter == 'Selection' ? 'selected' : '' }}>Selection</option>
                        <option value="Invited" {{ $statusFilter == 'Invited' ? 'selected' : '' }}>Invited</option>
                        <option value="Accepted" {{ $statusFilter == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="Rejected" {{ $statusFilter == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Finished" {{ $statusFilter == 'Finished' ? 'selected' : '' }}>Finished</option>
                        <option value="Withdrawn" {{ $statusFilter == 'Withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                    </select>
                </div>
                @if ($statusFilter)
                    <button type="button" class="btn btn-outline-primary" id="clearFilter">
                        <i class="bi bi-x-circle me-2"></i>
                        Hapus Filter
                    </button>
                @endif
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
                    <button class="nav-link" id="ratings-tab" data-bs-toggle="tab" data-bs-target="#ratings" type="button"
                        role="tab">
                        <i class="bi bi-star me-2"></i>Rating dan Review
                        <span class="tab-badge">{{ $ratingsFromCandidates->total() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#report" type="button"
                        role="tab">
                        <i class="bi bi-flag me-2"></i>Report
                        <span class="tab-badge">{{ $reviewsToReport->total() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#report" type="button"
                        role="tab">
                        <i class="bi bi-flag me-2"></i>Blacklist

                    </button>
                </li>
            </ul>

            <div class="tab-content" id="historyTabsContent">
                <!-- Tab Aplikasi -->
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
                                        <p class="candidate-email">
                                            <i class="bi bi-envelope"></i>
                                            {{ $application->candidate->email ?? 'Email Tidak Tersedia' }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $statusClass = match ($application->status) {
                                            'Withdrawn' => 'badge-withdrawn',
                                            'Selection' => 'badge-selection',
                                            'Invited' => 'badge-invited',
                                            'Accepted' => 'badge-accepted',
                                            'Rejected' => 'badge-rejected',
                                            'Finished' => 'badge-finished',
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
                                    <span>{{ $application->jobPosting->title ?? ($application->jobPosting->job_title ?? 'Tidak Tersedia') }}</span>
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
                                    <button class="btn btn-rated" disabled>
                                        <i class="bi bi-check-circle"></i>
                                        Sudah Diberi Rating
                                    </button>
                                @endif

                                @if ($application->candidate && $application->candidate->cv_path)
                                    <a href="{{ Storage::url($application->candidate->cv_path) }}"
                                        class="btn btn-outline-primary" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i>
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

                <!-- Tab Rating -->
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
                                        <p class="candidate-email">
                                            <i class="bi bi-envelope"></i>
                                            {{ $rating->candidate->email ?? 'Email Tidak Tersedia' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="rating-display">
                                    <span class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $rating->rating_company)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span>{{ $rating->rating_company }}/5</span>
                                </div>
                            </div>

                            <div class="history-meta">
                                <div class="meta-item">
                                    <i class="bi bi-briefcase-fill"></i>
                                    <strong>Posisi:</strong>
                                    <span>{{ $application->jobPosting->title ?? ($application->jobPosting->job_title ?? 'Tidak Tersedia') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-calendar-event"></i>
                                    <strong>Tanggal Rating:</strong>
                                    <span>{{ $rating->updated_at->format('d M Y') }}</span>
                                </div>
                            </div>

                            @if ($rating->review_company)
                                <div class="review-box">
                                    <h6>
                                        <i class="bi bi-chat-quote me-2"></i>
                                        Review dari Kandidat:
                                    </h6>
                                    <p class="review-text">{{ $rating->review_company }}</p>
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

                <!-- Tab Review -->
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
                                        <p class="candidate-email">
                                            <i class="bi bi-envelope"></i>
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
                                <h6>
                                    <i class="bi bi-chat-quote me-2"></i>
                                    Review dari Kandidat:
                                </h6>
                                <p class="review-text">{{ $review->review_company }}</p>
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

                <!-- Tab Report -->
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
                                        <p class="candidate-email">
                                            <i class="bi bi-envelope"></i>
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
                                <h6>
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
                                        <i class="bi bi-flag-fill"></i>
                                        Sudah Dilaporkan
                                    </button>
                                @else
                                    <button class="btn btn-warning btn-report-review"
                                        data-application-id="{{ $review->id }}"
                                        data-candidate-name="{{ $review->candidate->name ?? 'Kandidat' }}">
                                        <i class="bi bi-flag"></i>
                                        Laporkan Review
                                    </button>
                                @endif

                                @if ($isBlocked)
                                    <button class="btn btn-dark" disabled>
                                        <i class="bi bi-slash-circle-fill"></i>
                                        Sudah Diblokir
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-block-user"
                                        data-application-id="{{ $review->id }}"
                                        data-candidate-name="{{ $review->candidate->name ?? 'Kandidat' }}"
                                        data-candidate-user-id="{{ $review->candidate->user_id }}">
                                        <i class="bi bi-slash-circle"></i>
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

                <!-- Tab Accepted -->
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
                                        <p class="candidate-email">
                                            <i class="bi bi-envelope"></i>
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
                                    <span>{{ $accepted->jobPosting->job_title ?? 'Tidak Tersedia' }}</span>
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
                                    <button class="btn btn-rated" disabled>
                                        <i class="bi bi-check-circle"></i>
                                        Sudah Diberi Rating
                                    </button>
                                @endif

                                @if ($accepted->candidate && $accepted->candidate->cv_path)
                                    <a href="{{ Storage::url($accepted->candidate->cv_path) }}"
                                        class="btn btn-outline-primary" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i>
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

                <!-- Tab Rejected -->
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
                                        <p class="candidate-email">
                                            <i class="bi bi-envelope"></i>
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
                                    <span>{{ $rejected->jobPosting->job_title ?? 'Tidak Tersedia' }}</span>
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
                                    <button class="btn btn-rated" disabled>
                                        <i class="bi bi-check-circle"></i>
                                        Sudah Diberi Rating
                                    </button>
                                @endif

                                @if ($rejected->candidate && $rejected->candidate->cv_path)
                                    <a href="{{ Storage::url($rejected->candidate->cv_path) }}"
                                        class="btn btn-outline-primary" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Report Review Functionality
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

            // Block User Functionality
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

            // Tab Persistence and Synchronization
            const tabButtons = document.querySelectorAll('#historyTabs button[data-bs-toggle="tab"]');
            const savedTab = localStorage.getItem('companyHistoryActiveTab') || '#all';

            // Set initial tab
            const initialTabButton = document.querySelector(
                `#historyTabs button[data-bs-target="${savedTab}"]`);
            if (initialTabButton) {
                const tab = new bootstrap.Tab(initialTabButton);
                tab.show();
            }

            // Save tab on change
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function(e) {
                    const target = e.target.getAttribute('data-bs-target');
                    localStorage.setItem('companyHistoryActiveTab', target);
                });
            });

            // Status Filter Functionality
            const statusFilter = document.getElementById('statusFilter');
            const clearFilterBtn = document.getElementById('clearFilter');

            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const status = this.value;
                    const url = new URL(window.location.href);

                    if (status) {
                        url.searchParams.set('status', status);
                    } else {
                        url.searchParams.delete('status');
                    }
                    window.location.href = url.toString();
                });
            }

            if (clearFilterBtn) {
                clearFilterBtn.addEventListener('click', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('status');
                    window.location.href = url.toString();
                });
            }

            // Rate Candidate Functionality
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
        });
    </script>
@endsection
