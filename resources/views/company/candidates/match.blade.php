@extends('layouts.main')

@section('content')
    <style>
        .rating-summary-card {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .rating-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .rating-stars {
            font-size: 1.5rem;
            color: #fbbf24;
            margin-bottom: 0.5rem;
        }

        .rating-count {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .rating-bar-container {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .rating-bar-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .rating-bar-label {
            min-width: 60px;
            font-weight: 600;
            color: #6b7280;
        }

        .rating-bar {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin: 0 1rem;
        }

        .rating-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
        }

        .rating-bar-count {
            min-width: 40px;
            text-align: right;
            font-weight: 600;
            color: #4b5563;
        }

        .feedback-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f3f4f6;
            border-radius: 8px;
            margin: 0.25rem;
            font-weight: 600;
            color: #374151;
        }

        .feedback-badge i {
            color: var(--primary-blue);
        }

        .review-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .review-company {
            font-weight: 600;
            color: #111827;
            font-size: 1.05rem;
        }

        .review-job {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .review-rating .stars {
            color: #fbbf24;
            font-size: 1.1rem;
        }

        .review-text {
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .review-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
        }

        .review-date {
            font-size: 0.85rem;
            color: #9ca3af;
        }

        .feedback-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .feedback-tag {
            padding: 0.25rem 0.75rem;
            background: #ede9fe;
            color: #7c3aed;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .no-reviews {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .no-reviews i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .tab-content-rating {
            max-height: 500px;
            overflow-y: auto;
            padding: 1rem;
        }

        .tab-content-rating::-webkit-scrollbar {
            width: 6px;
        }

        .tab-content-rating::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }

        .tab-content-rating::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 10px;
        }

        .avatar-image-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-blue);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .avatar-circle-large {
            width: 120px;
            height: 120px;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .badge-lg {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .filter-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
        }

        .search-input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            padding-left: 2.5rem;
            width: 100%;
            font-size: 0.95rem;
        }

        .search-input:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .select2-container--default .select2-selection--single {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.5rem;
            height: auto;
            min-height: 48px;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--primary-blue);
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827;
            line-height: 1.5;
            padding-left: 0.5rem;
            font-size: 0.95rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
        }

        .select2-dropdown {
            border: 2px solid var(--primary-blue);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-blue);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            padding: 0.5rem;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        .candidate-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            height: 100%;
            border: 2px solid #f3f4f6;
        }

        .candidate-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--primary-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 auto 1rem;
        }

        .candidate-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .candidate-email {
            color: #6b7280;
            font-size: 0.85rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .match-score-container {
            text-align: center;
            margin: 1.5rem 0;
            padding: 1rem;
            background: var(--bg-blue);
            border-radius: 8px;
            border: 2px solid var(--light-blue);
        }

        .match-score {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .match-score.excellent {
            color: #10b981;
        }

        .match-score.good {
            color: #3b82f6;
        }

        .match-score.fair {
            color: #f59e0b;
        }

        .match-label {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }

        .match-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        .match-badge.excellent {
            background: #10b981;
            color: white;
        }

        .match-badge.good {
            background: #3b82f6;
            color: white;
        }

        .match-badge.fair {
            background: #f59e0b;
            color: white;
        }

        .info-section {
            margin: 1rem 0;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 8px;
        }

        .info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .skill-tag {
            display: inline-block;
            background: var(--light-blue);
            color: var(--primary-blue);
            padding: 0.35rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin: 0.25rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .info-item i {
            color: var(--primary-blue);
        }

        .btn-invite {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-view-profile {
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .modal-header-custom {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .detail-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: 2px solid #f3f4f6;
        }

        .detail-section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--light-blue);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-table {
            width: 100%;
        }

        .detail-table tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-table tr:last-child {
            border-bottom: none;
        }

        .detail-table td {
            padding: 0.875rem 0;
            vertical-align: top;
        }

        .detail-table td:first-child {
            font-weight: 600;
            color: #6b7280;
            width: 40%;
        }

        .detail-table td:last-child {
            color: #111827;
        }

        .badge-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-primary {
            background: var(--light-blue);
            color: var(--primary-blue);
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .nav-tabs {
            border-bottom: 2px solid #e5e7eb;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6b7280;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-blue);
            border-bottom-color: var(--primary-blue);
            background: transparent;
        }

        .modal-profile-header {
            background: linear-gradient(135deg, var(--bg-blue), white);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .modal-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .modal-email {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .portfolio-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 2px solid #e5e7eb;
        }

        .portfolio-title {
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .portfolio-desc {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .portfolio-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h4 {
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }

            .match-score {
                font-size: 2rem;
            }

            .detail-item {
                flex-direction: column;
                gap: 0.5rem;
            }

            .detail-value {
                text-align: left;
            }

            .filter-card {
                padding: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <div class="page-header">
            <div class="container">
                <h1 class="page-title">
                    <i class="bi bi-people-fill me-3"></i>
                    Kandidat yang Cocok
                </h1>
                <p class="page-subtitle">
                    Ajak Kandidat langsung untuk dapat diinvite
                </p>
            </div>
        </div>

        <div class="filter-card">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="filter-label">
                        <i class="bi bi-briefcase me-2"></i>Pilih Lowongan
                    </label>
                    <select class="form-select job-select" id="jobSelector">
                        @forelse($jobPostings as $job)
                            <option value="{{ $job->id }}"
                                {{ $selectedJob && $selectedJob->id == $job->id ? 'selected' : '' }}>
                                {{ $job->title }} - {{ $job->city->name ?? 'Lokasi tidak tersedia' }}
                                ({{ $job->applications->count() }} pelamar)
                            </option>
                        @empty
                            <option value="">Tidak ada lowongan aktif</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="filter-label">
                        <i class="bi bi-search me-2"></i>Cari Nama Kandidat
                    </label>
                    <div style="position: relative;">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="searchCandidate" class="search-input"
                            placeholder="Ketik nama kandidat...">
                    </div>
                </div>
            </div>
        </div>

        {{-- CANDIDATE CARDS --}}
        @if ($selectedJob)
            @if ($matchingCandidates->count() > 0)
                <div class="row g-4" id="candidateList">
                    @foreach ($matchingCandidates as $candidate)
                        <div class="col-md-6 col-lg-4 candidate-item" data-name="{{ strtolower($candidate->name) }}">
                            <div class="candidate-card">
                                {{-- Avatar --}}
                                <div class="candidate-avatar">
                                    {{ strtoupper(substr($candidate->name ?? 'U', 0, 1)) }}
                                </div>

                                {{-- Name & Email --}}
                                <h5 class="candidate-name">{{ $candidate->name }}</h5>
                                <p class="candidate-email">
                                    <i class="bi bi-envelope me-1"></i>
                                    {{ $candidate->user->email ?? 'Email tidak tersedia' }}
                                </p>

                                {{-- Match Score --}}
                                <div class="match-score-container">
                                    @php
                                        $scoreClass = 'fair';
                                        $scoreLabel = 'Cukup Cocok';
                                        if ($candidate->match_score >= 80) {
                                            $scoreClass = 'excellent';
                                            $scoreLabel = 'Sangat Cocok';
                                        } elseif ($candidate->match_score >= 60) {
                                            $scoreClass = 'good';
                                            $scoreLabel = 'Cocok';
                                        }
                                    @endphp
                                    <div class="match-score {{ $scoreClass }}">
                                        {{ number_format($candidate->match_score, 1) }}%
                                    </div>
                                    <div class="match-label">Tingkat Kecocokan</div>
                                    <span class="match-badge {{ $scoreClass }}">
                                        <i class="bi bi-star-fill me-1"></i>{{ $scoreLabel }}
                                    </span>
                                </div>

                                {{-- Skills --}}
                                @if ($candidate->skills->count() > 0)
                                    <div class="info-section">
                                        <div class="info-label">
                                            <i class="bi bi-tools me-1"></i>Skills
                                        </div>
                                        <div>
                                            @foreach ($candidate->skills->take(5) as $skill)
                                                <span class="skill-tag">{{ $skill->name }}</span>
                                            @endforeach
                                            @if ($candidate->skills->count() > 5)
                                                <span class="skill-tag">+{{ $candidate->skills->count() - 5 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Info --}}
                                <div class="info-section">
                                    <div class="info-item">
                                        <i class="bi bi-gender-ambiguous"></i>
                                        <strong>Gender:</strong>
                                        <span>{{ $candidate->gender ?? '-' }}</span>
                                    </div>
                                    @if ($candidate->birth_date)
                                        <div class="info-item">
                                            <i class="bi bi-calendar-event"></i>
                                            <strong>Lahir:</strong>
                                            <span>{{ \Carbon\Carbon::parse($candidate->birth_date)->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                    @if ($candidate->min_height || $candidate->min_weight)
                                        <div class="info-item">
                                            <i class="bi bi-rulers"></i>
                                            <strong>TB/BB:</strong>
                                            <span>{{ $candidate->min_height ?? '-' }} cm /
                                                {{ $candidate->min_weight ?? '-' }} kg</span>
                                        </div>
                                    @endif
                                    @if ($candidate->min_salary)
                                        <div class="info-item">
                                            <i class="bi bi-cash"></i>
                                            <strong>Gaji Min:</strong>
                                            <span>Rp {{ number_format($candidate->min_salary, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Preferensi Lokasi --}}
                                @if ($candidate->preferred_cities && $candidate->preferred_cities->count() > 0)
                                    <div class="info-section">
                                        <div class="info-label">
                                            <i class="bi bi-geo-alt me-1"></i>Lokasi Preferensi
                                        </div>
                                        <div>
                                            @foreach ($candidate->preferred_cities->take(3) as $city)
                                                <span class="skill-tag">{{ $city->name }}</span>
                                            @endforeach
                                            @if ($candidate->preferred_cities->count() > 3)
                                                <span
                                                    class="skill-tag">+{{ $candidate->preferred_cities->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Industri Diminati --}}
                                @if ($candidate->preferred_industries && $candidate->preferred_industries->count() > 0)
                                    <div class="info-section">
                                        <div class="info-label">
                                            <i class="bi bi-building me-1"></i>Industri Diminati
                                        </div>
                                        <div>
                                            @foreach ($candidate->preferred_industries->take(3) as $industry)
                                                <span class="skill-tag">{{ $industry->name }}</span>
                                            @endforeach
                                            @if ($candidate->preferred_industries->count() > 3)
                                                <span
                                                    class="skill-tag">+{{ $candidate->preferred_industries->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Tipe Pekerjaan --}}
                                @if ($candidate->preferred_type_jobs && $candidate->preferred_type_jobs->count() > 0)
                                    <div class="info-section">
                                        <div class="info-label">
                                            <i class="bi bi-briefcase me-1"></i>Tipe Pekerjaan
                                        </div>
                                        <div>
                                            @foreach ($candidate->preferred_type_jobs->take(3) as $typeJob)
                                                <span class="skill-tag">{{ $typeJob->name }}</span>
                                            @endforeach
                                            @if ($candidate->preferred_type_jobs->count() > 3)
                                                <span
                                                    class="skill-tag">+{{ $candidate->preferred_type_jobs->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Hari Kerja --}}
                                @if ($candidate->days && $candidate->days->count() > 0)
                                    <div class="info-section">
                                        <div class="info-label">
                                            <i class="bi bi-calendar-week me-1"></i>Hari Kerja
                                        </div>
                                        <div>
                                            @foreach ($candidate->days->take(4) as $day)
                                                <span class="skill-tag">{{ $day->name }}</span>
                                            @endforeach
                                            @if ($candidate->days->count() > 4)
                                                <span class="skill-tag">+{{ $candidate->days->count() - 4 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Deskripsi Singkat --}}
                                @if ($candidate->description)
                                    <div class="info-section">
                                        <div class="info-label">
                                            <i class="bi bi-file-text me-1"></i>Tentang
                                        </div>
                                        <p class="mb-0" style="font-size: 0.85rem; color: #4b5563; line-height: 1.5;">
                                            {{ Str::limit($candidate->description, 120) }}
                                        </p>
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <button class="btn btn-invite btn-invite-candidate"
                                    data-candidate-id="{{ $candidate->id }}" data-candidate-name="{{ $candidate->name }}"
                                    data-job-id="{{ $selectedJob->id }}">
                                    <i class="bi bi-send"></i>
                                    Undang Kandidat
                                </button>

                                <button class="btn btn-view-profile"
                                    onclick="viewCandidateDetail({{ $candidate->candidates_id ?? $candidate->id }})">
                                    <i class="bi bi-eye"></i>
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $matchingCandidates->appends(['job_id' => $selectedJob->id])->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <h4>Tidak Ada Kandidat yang Cocok</h4>
                    <p>Belum ada kandidat yang sesuai dengan kriteria lowongan ini</p>
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-briefcase"></i>
                <h4>Tidak Ada Lowongan Aktif</h4>
                <p>Silakan buat lowongan terlebih dahulu untuk melihat kandidat yang cocok</p>
            </div>
        @endif
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // INITIALIZE SELECT2
        $('#jobSelector').select2({
            placeholder: 'Cari lowongan...',
            allowClear: false,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada hasil ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });

        // JOB SELECTOR CHANGE
        $('#jobSelector').on('change', function() {
            const jobId = $(this).val();
            if (jobId) {
                $('#loadingOverlay').addClass('active');
                window.location.href = `{{ route('company.candidates.match') }}?job_id=${jobId}`;
            }
        });

        // SEARCH CANDIDATE BY NAME
        $('#searchCandidate').on('keyup', function() {
            const searchValue = $(this).val().toLowerCase();

            $('.candidate-item').each(function() {
                const candidateName = $(this).data('name');

                if (candidateName.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Show empty state if no results
            const visibleCandidates = $('.candidate-item:visible').length;
            if (visibleCandidates === 0 && searchValue !== '') {
                if ($('#noSearchResults').length === 0) {
                    $('#candidateList').after(`
                        <div id="noSearchResults" class="empty-state">
                            <i class="bi bi-search"></i>
                            <h4>Tidak Ada Hasil</h4>
                            <p>Tidak ada kandidat dengan nama "${searchValue}"</p>
                        </div>
                    `);
                }
            } else {
                $('#noSearchResults').remove();
            }
        });

        // INVITE CANDIDATE
        $('.btn-invite-candidate').on('click', async function() {
            const candidateId = $(this).data('candidate-id');
            const candidateName = $(this).data('candidate-name');
            const jobId = $(this).data('job-id');

            const {
                value: message
            } = await Swal.fire({
                title: 'Undang Kandidat',
                html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label fw-bold">Kandidat:</label>
                    <p class="mb-0">${candidateName}</p>
                </div>
                <div class="mb-3">
                    <label for="inviteMessage" class="form-label fw-bold">
                        Pesan (Opsional)
                    </label>
                    <textarea id="inviteMessage" class="form-control" rows="4"
                        placeholder="Tulis pesan untuk kandidat..."
                        maxlength="500"></textarea>
                    <small class="text-muted">Maksimal 500 karakter</small>
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Kandidat akan menerima notifikasi undangan dari Anda
                </div>
            </div>
        `,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-send me-2"></i>Kirim Undangan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#14489b',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    return document.getElementById('inviteMessage').value.trim();
                }
            });

            if (message === undefined) return;

            try {
                const response = await fetch(
                    "{{ route('company.candidates.invite.post', ':id') }}".replace(':id',
                        candidateId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            job_posting_id: jobId,
                            message: message
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
                    throw new Error(data.message || 'Gagal mengirim undangan');
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

    async function viewCandidateDetail(candidateId) {
        try {
            const candidateUrl = `{{ route('company.candidates.match.detail', ':id') }}`.replace(':id',
                candidateId);

            const candidateResponse = await fetch(candidateUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!candidateResponse.ok) {
                throw new Error(`HTTP error! status: ${candidateResponse.status}`);
            }

            const candidateResult = await candidateResponse.json();

            if (!candidateResult.success) {
                throw new Error(candidateResult.message || 'Gagal memuat data kandidat');
            }

            const candidate = candidateResult.candidate;

            const ratingUrl = `{{ route('company.candidates.match.rating-detail', ':id') }}`.replace(':id',
                candidateId);

            const ratingResponse = await fetch(ratingUrl);

            if (!ratingResponse.ok) {
                throw new Error('Failed to load rating data');
            }

            const ratingData = await ratingResponse.json();

            if (!ratingData.success) {
                throw new Error(ratingData.message || 'Failed to load rating data');
            }

            const rating = ratingData.data;

            let avatarHTML = '';
            if (candidate.user && candidate.user.photo) {
                avatarHTML = `
                <img src="/storage/${candidate.user.photo}"
                     alt="${candidate.name}"
                     class="avatar-image-large mx-auto mb-3"
                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="avatar-circle-large mx-auto mb-3" style="display: none;">
                    ${candidate.name.substring(0, 2).toUpperCase()}
                </div>
            `;
            } else {
                avatarHTML = `
                <div class="avatar-circle-large mx-auto mb-3">
                    ${candidate.name.substring(0, 2).toUpperCase()}
                </div>
            `;
            }

            function generateStars(rating) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        stars += '<i class="bi bi-star-fill"></i>';
                    } else if (i - 0.5 <= rating) {
                        stars += '<i class="bi bi-star-half"></i>';
                    } else {
                        stars += '<i class="bi bi-star"></i>';
                    }
                }
                return stars;
            }

            const ratingTabContent = `
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="rating-summary-card">
                        <div class="rating-number">${rating.average_rating}</div>
                        <div class="rating-stars">${generateStars(rating.average_rating)}</div>
                        <div class="rating-count">Dari ${rating.total_ratings} rating</div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="rating-bar-container">
                        ${[5, 4, 3, 2, 1].map(star => {
                            const count = rating.rating_breakdown[star];
                            const percentage = rating.total_ratings > 0 ? (count / rating.total_ratings * 100) : 0;
                            return `
                                <div class="rating-bar-item">
                                    <div class="rating-bar-label">${star} <i class="bi bi-star-fill" style="color: #fbbf24;"></i></div>
                                    <div class="rating-bar">
                                        <div class="rating-bar-fill" style="width: ${percentage}%"></div>
                                    </div>
                                    <div class="rating-bar-count">${count}</div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            </div>

            ${rating.feedback_counts.length > 0 ? `
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-tags me-2"></i>Feedback dari Perusahaan</h6>
                    <div class="d-flex flex-wrap gap-2">
                        ${rating.feedback_counts.map(fb => `
                            <span class="feedback-badge">
                                <i class="bi bi-check-circle-fill"></i>
                                ${fb.name} (${fb.count}x)
                            </span>
                        `).join('')}
                    </div>
                </div>
            ` : ''}

            <div>
                <h6 class="fw-bold mb-3"><i class="bi bi-chat-quote me-2"></i>Review dari Perusahaan</h6>
                <div class="tab-content-rating">
                    ${rating.reviews.length > 0 ? rating.reviews.map(review => `
                        <div class="review-card">
                            <div class="review-header">
                                <div>
                                    <div class="review-company">${review.company_name}</div>
                                    <div class="review-job">${review.job_title}</div>
                                </div>
                                <div class="review-rating">
                                    <span class="stars">${generateStars(review.rating)}</span>
                                    <strong>${review.rating}.0</strong>
                                </div>
                            </div>
                            ${review.review ? `
                                <div class="review-text">${review.review}</div>
                            ` : '<div class="review-text text-muted fst-italic">Tidak ada review tertulis</div>'}
                            <div class="review-footer">
                                <div class="review-date">
                                    <i class="bi bi-calendar me-1"></i>${review.date}
                                </div>
                                ${review.feedbacks.length > 0 ? `
                                    <div class="feedback-tags">
                                        ${review.feedbacks.map(fb => `
                                            <span class="feedback-tag">${fb}</span>
                                        `).join('')}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `).join('') : `
                        <div class="no-reviews">
                            <i class="bi bi-inbox"></i>
                            <p class="fw-semibold">Belum ada review</p>
                            <small>Kandidat ini belum menerima review dari perusahaan</small>
                        </div>
                    `}
                </div>
            </div>
        `;

            let skillsHtml = '';
            if (candidate.skills && candidate.skills.length > 0) {
                skillsHtml = candidate.skills.map(skill =>
                    `<span class="badge bg-primary">${skill.name}</span>`
                ).join(' ');
            } else {
                skillsHtml = '<p class="text-muted mb-0">Tidak ada skills</p>';
            }

            let citiesHtml = '';
            if (candidate.preferred_cities && candidate.preferred_cities.length > 0) {
                citiesHtml = candidate.preferred_cities.map(city =>
                    `<span class="badge bg-success">${city.name}</span>`
                ).join(' ');
            } else {
                citiesHtml = '<p class="text-muted mb-0">Tidak ada preferensi kota</p>';
            }

            let industriesHtml = '';
            if (candidate.preferred_industries && candidate.preferred_industries.length > 0) {
                industriesHtml = candidate.preferred_industries.map(ind =>
                    `<span class="badge bg-warning text-dark">${ind.name}</span>`
                ).join(' ');
            } else {
                industriesHtml = '<p class="text-muted mb-0">Tidak ada preferensi industri</p>';
            }

            let typeJobsHtml = '';
            if (candidate.preferred_type_jobs && candidate.preferred_type_jobs.length > 0) {
                typeJobsHtml = candidate.preferred_type_jobs.map(type =>
                    `<span class="badge bg-info text-dark">${type.name}</span>`
                ).join(' ');
            } else {
                typeJobsHtml = '<p class="text-muted mb-0">Tidak ada preferensi tipe pekerjaan</p>';
            }

            let daysHtml = '';
            if (candidate.days && candidate.days.length > 0) {
                daysHtml = candidate.days.map(day =>
                    `<span class="badge bg-secondary">${day.name}</span>`
                ).join(' ');
            } else {
                daysHtml = '<p class="text-muted mb-0">Tidak ada preferensi hari kerja</p>';
            }

            let portfolioHtml = '';
            if (candidate.portofolios && candidate.portofolios.length > 0) {
                portfolioHtml = candidate.portofolios.map(portfolio => {
                    const fileUrl = portfolio.file ? `/storage/${portfolio.file}` : null;
                    return `
                    <div class="col-md-4 mb-3">
                        <div class="portfolio-card text-center p-3 border rounded">
                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 3rem;"></i>
                            <p class="small mt-2 mb-2">${portfolio.title || 'Portfolio'}</p>
                            ${fileUrl ? `
                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            ` : '<p class="text-muted mb-0">File tidak tersedia</p>'}
                        </div>
                    </div>
                `;
                }).join('');
            } else {
                portfolioHtml =
                    '<div class="col-12"><p class="text-muted text-center"><i class="bi bi-inbox"></i> Tidak ada portfolio</p></div>';
            }

            const modalContent = `
            <div class="row">
                <div class="col-12">
                    <div class="modal-profile-header">
                        ${avatarHTML}
                        <h4 class="modal-name">${candidate.name}</h4>
                        <p class="modal-email">
                            <i class="bi bi-envelope me-2"></i>${candidate.user?.email || '-'}
                        </p>
                    </div>
                </div>
                
                <div class="col-12">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profileTab" type="button">
                                <i class="bi bi-person me-2"></i>Profil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ratingTab" type="button">
                                <i class="bi bi-star me-2"></i>Rating & Review
                                <span class="badge bg-primary ms-1">${rating.total_ratings}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profileTab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-info-circle"></i>Informasi Pribadi
                                        </h5>
                                        <table class="detail-table">
                                            <tr>
                                                <td><i class="bi bi-telephone me-2"></i>Telepon</td>
                                                <td>${candidate.phone_number || '-'}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-gender-ambiguous me-2"></i>Jenis Kelamin</td>
                                                <td>${candidate.gender || '-'}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-calendar me-2"></i>Tanggal Lahir</td>
                                                <td>${candidate.birth_date ? new Date(candidate.birth_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-rulers me-2"></i>Tinggi/Berat</td>
                                                <td>${candidate.min_height || '-'} cm / ${candidate.min_weight || '-'} kg</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-cash me-2"></i>Ekspektasi Gaji</td>
                                                <td>Rp ${candidate.min_salary ? parseInt(candidate.min_salary).toLocaleString('id-ID') : '-'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-translate"></i>Kemampuan Bahasa
                                        </h5>
                                        <table class="detail-table">
                                            <tr>
                                                <td><i class="bi bi-flag me-2"></i>English</td>
                                                <td><span class="badge-custom badge-info">${candidate.level_english || '-'}</span></td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-flag me-2"></i>Mandarin</td>
                                                <td><span class="badge-custom badge-info">${candidate.level_mandarin || '-'}</span></td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-star"></i>Rating
                                        </h5>
                                        <table class="detail-table">
                                            <tr>
                                                <td><i class="bi bi-star-fill me-2"></i>Rating</td>
                                                <td>
                                                    <strong style="font-size: 1.25rem; color: var(--primary-blue);">${rating.average_rating}</strong>
                                                    <span style="color: #6b7280;"> / 5.0</span>
                                                    <div style="color: #fbbf24; font-size: 0.9rem;">${generateStars(rating.average_rating)}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                ${skillsHtml !== '<p class="text-muted mb-0">Tidak ada skills</p>' ? `
                                <div class="col-12">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-tools"></i>Keterampilan
                                        </h5>
                                        <div class="badge-group">${skillsHtml.replace(/badge bg-primary/g, 'badge-custom badge-primary')}</div>
                                    </div>
                                </div>
                                ` : ''}

                                ${citiesHtml !== '<p class="text-muted mb-0">Tidak ada preferensi kota</p>' ? `
                                <div class="col-12">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-geo-alt"></i>Preferensi Lokasi
                                        </h5>
                                        <div class="badge-group">${citiesHtml.replace(/badge bg-success/g, 'badge-custom badge-success')}</div>
                                    </div>
                                </div>
                                ` : ''}

                                ${industriesHtml !== '<p class="text-muted mb-0">Tidak ada preferensi industri</p>' ? `
                                <div class="col-12">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-briefcase"></i>Industri Diminati
                                        </h5>
                                        <div class="badge-group">${industriesHtml.replace(/badge bg-warning text-dark/g, 'badge-custom badge-warning')}</div>
                                    </div>
                                </div>
                                ` : ''}

                                ${typeJobsHtml !== '<p class="text-muted mb-0">Tidak ada preferensi tipe pekerjaan</p>' ? `
                                <div class="col-12">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-briefcase-fill"></i>Tipe Pekerjaan
                                        </h5>
                                        <div class="badge-group">${typeJobsHtml.replace(/badge bg-info text-dark/g, 'badge-custom badge-info')}</div>
                                    </div>
                                </div>
                                ` : ''}

                                ${daysHtml !== '<p class="text-muted mb-0">Tidak ada preferensi hari kerja</p>' ? `
                                <div class="col-12">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-calendar-week"></i>Hari Kerja Preferensi
                                        </h5>
                                        <div class="badge-group">${daysHtml.replace(/badge bg-secondary/g, 'badge-custom badge-secondary')}</div>
                                    </div>
                                </div>
                                ` : ''}

                                ${candidate.description ? `
                                <div class="col-12">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-file-text"></i>Deskripsi
                                        </h5>
                                        <p style="color: #4b5563; line-height: 1.7; margin: 0;">${candidate.description}</p>
                                    </div>
                                </div>
                                ` : ''}

                                <div class="col-12">
                                    <div class="detail-section">
                                        <h5 class="detail-section-title">
                                            <i class="bi bi-folder"></i>Portfolio
                                        </h5>
                                        <div class="row g-3">${portfolioHtml}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="ratingTab">
                            ${ratingTabContent}
                        </div>
                    </div>
                </div>
            </div>
        `;

            Swal.close();

            Swal.fire({
                html: modalContent,
                width: '1200px',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'candidate-detail-modal'
                }
            });

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message || 'Terjadi kesalahan saat memuat detail kandidat',
                confirmButtonColor: '#dc3545'
            });
        }
    }
</script>
