@extends('layouts.main')

@section('content')

    <style>
        .rating-summary-card {
            background: #395eaa;
            color: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
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
            transition: width 0.3s ease;
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
            color: #285099;
        }

        .review-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
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
            color: #3a93ed;
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

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .avatar-image-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-blue);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Header Styles */
        .job-header {
            background: var(--primary-blue);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .job-header h2 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .job-header .company-info {
            opacity: 0.95;
            font-size: 1.1rem;
        }

        /* Section Styles */
        .detail-section {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .detail-section h5 {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--light-blue);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-section h5 i {
            font-size: 1.2rem;
        }

        /* Info Row Styles */
        .info-row {
            display: flex;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
            min-width: 180px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-value {
            color: #111827;
            flex: 1;
            font-weight: 500;
        }

        /* Badge Styles */
        .badge-item {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin: 0.25rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 6px;
        }

        .badge-primary {
            background: var(--primary-blue);
            color: white;
        }

        .badge-success {
            background: #10b981;
            color: white;
        }

        .badge-warning {
            background: #f59e0b;
            color: white;
        }

        .badge-danger {
            background: #ef4444;
            color: white;
        }

        .badge-info {
            background: #06b6d4;
            color: white;
        }

        /* Button Styles */
        .btn-primary-custom {
            background: var(--primary-blue);
            border: none;
            color: white;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            background: var(--dark-blue);
        }

        /* Status Card */
        .status-card {
            background: var(--bg-blue);
            border-left: 4px solid var(--primary-blue);
        }

        /* Applicant Card */
        .applicant-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
            background: white;
        }

        .applicant-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .avatar-circle {
            width: 55px;
            height: 55px;
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-blue);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            color: white;
            border-radius: 50%;
        }

        /* Stats Box */
        .stats-box {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            border: 2px solid var(--light-blue);
            transition: all 0.3s;
        }

        .stats-box .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .stats-box .stats-label {
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        /* Timeline Style */
        .timeline-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--bg-blue);
            border-radius: 6px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-blue);
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        /* Salary Highlight */
        .salary-highlight {
            background: #fef3c7;
            padding: 1.5rem;
            border-radius: 8px;
            border: 2px solid #fbbf24;
            text-align: center;
            margin: 1rem 0;
        }

        .salary-highlight .salary-amount {
            font-size: 2rem;
            font-weight: 700;
            color: #92400e;
        }

        .salary-highlight .salary-label {
            color: #78350f;
            font-weight: 600;
        }

        /* Requirement Grid */
        .requirement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .requirement-item {
            background: var(--bg-blue);
            padding: 1rem;
            border-radius: 6px;
            border-left: 3px solid var(--primary-blue);
        }

        /* Empty State */
        .empty-applicants {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .empty-applicants i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Modal Styles */
        .modal-header {
            background: var(--primary-blue);
            color: white;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Search Box in Modal */
        .search-box {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        /* Applicant Card in Modal */
        .modal-applicant-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
            background: white;
        }

        .modal-applicant-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .avatar-circle-small {
            width: 50px;
            height: 50px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
        }

        .badge-lg {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .portfolio-card {
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s;
        }

        .portfolio-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .salary-type-badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            background: #8b5cf6;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Info Table */
        .info-table {
            width: 100%;
            margin-top: 1rem;
        }

        .info-table thead {
            background: var(--bg-blue);
        }

        .info-table thead th {
            color: var(--primary-blue);
            font-weight: 600;
            padding: 0.75rem;
            border: none;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .info-table tbody td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .info-table tbody tr:hover {
            background-color: var(--bg-blue);
        }

        .benefit-type-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .benefit-cash {
            background: #10b981;
            color: white;
        }

        .benefit-in-kind {
            background: #06b6d4;
            color: white;
        }

        .time-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .time-start {
            background: var(--primary-blue);
            color: white;
        }

        .time-end {
            background: #f59e0b;
            color: white;
        }
    </style>

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="job-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h2>{{ $job->title }}</h2>
                    <p class="company-info mb-3">
                        <i class="bi bi-building me-2"></i>{{ $company->name }}
                        <span class="mx-3">|</span>
                        <i class="bi bi-geo-alt me-2"></i>{{ $job->city ? $job->city->name : '-' }}
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        @if ($job->status == 'Open')
                            <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Open</span>
                        @elseif($job->status == 'Closed')
                            <span class="badge badge-danger"><i class="bi bi-x-circle me-1"></i>Closed</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-file-earmark me-1"></i>Draft</span>
                        @endif

                        @if ($job->verification_status == 'Approved')
                            <span class="badge badge-info"><i class="bi bi-shield-check me-1"></i>Verified</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('company.dashboard') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Quick Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="stats-box">
                            <div class="stats-number">{{ $job->applications->count() }}</div>
                            <div class="stats-label">Total Pelamar</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-box">
                            <div class="stats-number">{{ $job->slot }}</div>
                            <div class="stats-label">Posisi Tersedia</div>
                        </div>
                    </div>
                </div>

                {{-- ✅ REVISI: Salary Highlight with Type --}}
                <div class="salary-highlight">
                    <div class="salary-label">Penawaran Gaji</div>
                    <div class="salary-amount">Rp {{ number_format($job->salary, 0, ',', '.') }}</div>
                    <div class="mt-2">
                        <span class="salary-type-badge">
                            <i class="bi bi-calendar-check"></i>
                            {{ $job->type_salary == 'total' ? 'Total' : 'Per Hari' }}
                        </span>
                    </div>
                </div>

                <!-- Informasi Dasar -->
                <div class="detail-section">
                    <h5><i class="bi bi-info-circle"></i>Informasi Dasar</h5>
                    <div class="info-row">
                        <div class="info-label">Industri</div>
                        <div class="info-value">{{ $job->industry ? $job->industry->name : '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tipe Pekerjaan</div>
                        <div class="info-value">
                            <span class="badge badge-primary">{{ $job->typeJobs ? $job->typeJobs->name : '-' }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat Lengkap</div>
                        <div class="info-value">{{ $job->address }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value">{{ $job->description }}</div>
                    </div>
                </div>

                <!-- Persyaratan Kandidat -->
                <div class="detail-section">
                    <h5><i class="bi bi-person-check"></i>Persyaratan Kandidat</h5>
                    <div class="requirement-grid">
                        <div class="requirement-item">
                            <i class="bi bi-gender-ambiguous text-primary me-2"></i>
                            <strong>Jenis Kelamin:</strong><br>
                            {{ $job->gender == 'All' ? 'Semua' : ($job->gender == 'Male' ? 'Laki-laki' : 'Perempuan') }}
                        </div>
                        <div class="requirement-item">
                            <i class="bi bi-calendar-check text-primary me-2"></i>
                            <strong>Usia:</strong><br>
                            {{ $job->min_age }} - {{ $job->max_age }} tahun
                        </div>
                        <div class="requirement-item">
                            <i class="bi bi-rulers text-primary me-2"></i>
                            <strong>Tinggi Badan:</strong><br>
                            Min. {{ $job->min_height }} cm
                        </div>
                        <div class="requirement-item">
                            <i class="bi bi-speedometer text-primary me-2"></i>
                            <strong>Berat Badan:</strong><br>
                            Min. {{ $job->min_weight }} kg
                        </div>
                        <div class="requirement-item">
                            <i class="bi bi-translate text-primary me-2"></i>
                            <strong>Bahasa Inggris:</strong><br>
                            <span class="badge badge-info">{{ ucfirst($job->level_english) }}</span>
                        </div>
                        <div class="requirement-item">
                            <i class="bi bi-translate text-primary me-2"></i>
                            <strong>Bahasa Mandarin:</strong><br>
                            <span class="badge badge-info">{{ ucfirst($job->level_mandarin) }}</span>
                        </div>
                        <div class="requirement-item">
                            <i class="bi bi-camera-video text-primary me-2"></i>
                            <strong>Wawancara:</strong><br>
                            @if ($job->has_interview)
                                <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Ada</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Tidak Ada</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Keterampilan -->
                @if ($job->skills->count() > 0)
                    <div class="detail-section">
                        <h5><i class="bi bi-tools"></i>Keterampilan yang Dibutuhkan</h5>
                        <div class="d-flex flex-wrap">
                            @foreach ($job->skills as $skill)
                                <span class="badge-item badge-primary">
                                    <i class="bi bi-check-circle"></i>{{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ✅ REVISI: Benefit with Type & Amount --}}
                @if ($job->benefits->count() > 0)
                    <div class="detail-section">
                        <h5><i class="bi bi-gift"></i>Benefit & Fasilitas</h5>
                        <div class="table-responsive">
                            <table class="info-table table table-hover">
                                <thead>
                                    <tr>
                                        <th width="40%">Benefit</th>
                                        <th width="30%">Tipe</th>
                                        <th width="30%">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($job->benefits as $jobBenefit)
                                        <tr>
                                            <td>
                                                <i class="bi bi-gift-fill text-primary me-2"></i>
                                                <strong>{{ $jobBenefit->benefit->name ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                @if ($jobBenefit->benefit_type)
                                                    <span
                                                        class="benefit-type-badge {{ $jobBenefit->benefit_type == 'cash' ? 'benefit-cash' : 'benefit-in-kind' }}">
                                                        <i
                                                            class="bi {{ $jobBenefit->benefit_type == 'cash' ? 'bi-cash-coin' : 'bi-box-seam' }}"></i>
                                                        {{ $jobBenefit->benefit_type == 'cash' ? 'Cash' : 'In Kind' }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($jobBenefit->amount)
                                                    <strong>{{ $jobBenefit->amount }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- ✅ REVISI: Job Dates with Time --}}
                @if ($job->jobDatess->count() > 0)
                    <div class="detail-section">
                        <h5><i class="bi bi-calendar-week"></i>Jadwal Kerja</h5>
                        <div class="table-responsive">
                            <table class="info-table table table-hover">
                                <thead>
                                    <tr>
                                        <th width="25%">Hari</th>
                                        <th width="25%">Tanggal</th>
                                        <th width="25%">Jam Mulai</th>
                                        <th width="25%">Jam Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($job->jobDatess as $jobDate)
                                        <tr>
                                            <td>
                                                <i class="bi bi-calendar-day text-primary me-2"></i>
                                                <strong>{{ $jobDate->day->name ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($jobDate->date)->format('d M Y') }}
                                            </td>
                                            <td>
                                                <span class="time-badge time-start">
                                                    <i class="bi bi-clock"></i>
                                                    {{ \Carbon\Carbon::parse($jobDate->start_time)->format('H:i') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="time-badge time-end">
                                                    <i class="bi bi-clock-fill"></i>
                                                    {{ \Carbon\Carbon::parse($jobDate->end_time)->format('H:i') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Periode Rekrutmen -->
                <div class="detail-section">
                    <h5><i class="bi bi-calendar-range"></i>Periode Rekrutmen</h5>
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                        <div>
                            <strong>Tanggal Buka</strong><br>
                            <span
                                class="text-primary fw-bold">{{ \Carbon\Carbon::parse($job->open_recruitment)->format('d F Y') }}</span>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <div>
                            <strong>Tanggal Tutup</strong><br>
                            <span
                                class="text-danger fw-bold">{{ \Carbon\Carbon::parse($job->close_recruitment)->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Status Card -->
                <div class="detail-section status-card">
                    <h5><i class="bi bi-gear"></i>Status & Informasi</h5>
                    <div class="info-row">
                        <div class="info-label">Status Verifikasi</div>
                        <div class="info-value">
                            @if ($job->verification_status == 'Approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($job->verification_status == 'Rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @elseif($job->verification_status == 'Finished')
                                <span class="badge badge-danger">Finished</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Wawancara</div>
                        <div class="info-value">
                            @if ($job->has_interview)
                                <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Ada</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Tidak Ada</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Dibuat Pada</div>
                        <div class="info-value">
                            <small>{{ $job->created_at->format('d F Y H:i') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Applicants List -->
                <div class="detail-section">
                    <h5><i class="bi bi-people"></i>Daftar Pelamar ({{ $job->applications->count() }})</h5>

                    @if ($job->applications->count() > 0)
                        @foreach ($job->applications->take(5) as $application)
                            @php
                                $candidate = $application->candidate;
                                $user = $candidate ? $candidate->user : null;
                                $initials = $user ? strtoupper(substr($user->name, 0, 2)) : '??';
                            @endphp
                            <div class="applicant-card view-detail-sidebar-btn"
                                data-candidate-id="{{ $candidate ? $candidate->id : 0 }}"
                                data-application-id="{{ $application->id }}" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle avatar-circle me-3">
                                        {{ $initials }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $user ? $user->name : 'Unknown' }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>{{ $user ? $user->email : '-' }}
                                        </small>
                                    </div>
                                    <div>
                                        @if (in_array($application->status, ['Applied', 'Pending']))
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($application->status == 'Selection')
                                            <span class="badge badge-info">Seleksi</span>
                                        @elseif($application->status == 'Invited')
                                            <span class="badge badge-info">Invited</span>
                                        @elseif($application->status == 'Accepted')
                                            <span class="badge badge-success">Diterima</span>
                                        @elseif($application->status == 'Finished')
                                            <span class="badge badge-primary">Finished</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($job->applications->count() > 5)
                            <div class="text-center mt-3">
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#allApplicantsModal">
                                    <i class="bi bi-eye me-1"></i>Lihat Semua ({{ $job->applications->count() }})
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="empty-applicants">
                            <i class="bi bi-inbox"></i>
                            <p class="fw-semibold">Belum ada pelamar</p>
                            <small>Lowongan ini belum menerima aplikasi</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL  APPLICANTS --}}
    <div class="modal fade" id="allApplicantsModal" tabindex="-1" aria-hidden="true"
        data-job-status="{{ $job->status }}">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-people me-2"></i>Semua Pelamar ({{ $job->applications->count() }})
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="search-box">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchApplicant"
                                placeholder="Cari nama atau email kandidat...">
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary filter-status active" data-status="all">
                                Semua ({{ $job->applications->count() }})
                            </button>
                            <button class="btn btn-sm btn-outline-warning filter-status" data-status="Applied">
                                Pending ({{ $job->applications->whereIn('status', ['Applied', 'Pending'])->count() }})
                            </button>
                            @if ($job->has_interview)
                                <button class="btn btn-sm btn-outline-info filter-status" data-status="Selection">
                                    Seleksi ({{ $job->applications->where('status', 'Selection')->count() }})
                                </button>
                            @endif
                            <button class="btn btn-sm btn-outline-success filter-status" data-status="Accepted">
                                Diterima ({{ $job->applications->where('status', 'Accepted')->count() }})
                            </button>
                            <button class="btn btn-sm btn-outline-danger filter-status" data-status="Rejected">
                                Ditolak ({{ $job->applications->where('status', 'Rejected')->count() }})
                            </button>
                        </div>
                    </div>

                    <div id="applicantsList">
                        @foreach ($job->applications as $application)
                            @php
                                $candidate = $application->candidate;
                                $user = $candidate ? $candidate->user : null;
                                $initials = $user ? strtoupper(substr($user->name, 0, 2)) : '??';
                            @endphp
                            <div class="modal-applicant-card applicant-item application-card-{{ $application->id }}"
                                data-status="{{ $application->status }}"
                                data-name="{{ $user ? strtolower($user->name) : '' }}"
                                data-email="{{ $user ? strtolower($user->email) : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-small me-3">
                                        {{ $initials }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $user ? $user->name : 'Unknown' }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>{{ $user ? $user->email : '-' }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar me-1"></i>Melamar:
                                            {{ $application->created_at->format('d M Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span
                                            class="badge application-status-badge-{{ $application->id }} mb-2
        @if (in_array($application->status, ['Applied', 'Pending'])) badge-warning
        @elseif($application->status == 'Selection') badge-info
        @elseif($application->status == 'Accepted') badge-success
        @elseif($application->status == 'Finished') badge-primary
        @else badge-danger @endif">
                                            @if (in_array($application->status, ['Applied', 'Pending']))
                                                Pending
                                            @elseif($application->status == 'Selection')
                                                Seleksi
                                            @elseif($application->status == 'Accepted')
                                                Diterima
                                            @elseif($application->status == 'Finished')
                                                Finished
                                            @else
                                                Ditolak
                                            @endif
                                        </span>
                                        <br>
                                        <div class="btn-group" role="group">
                                            @if (in_array($application->status, ['Applied', 'Pending']))
                                                @if ($job->has_interview)
                                                    <button class="btn btn-sm btn-info select-btn"
                                                        data-application-id="{{ $application->id }}"
                                                        title="Pilih untuk Seleksi"
                                                        {{ $job->status == 'Closed' ? 'disabled' : '' }}>
                                                        <i class="bi bi-check2-square"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-success accept-btn"
                                                        data-application-id="{{ $application->id }}" title="Terima"
                                                        {{ $job->status == 'Closed' ? 'disabled' : '' }}>
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-danger reject-btn"
                                                    data-application-id="{{ $application->id }}" title="Tolak"
                                                    {{ $job->status == 'Closed' ? 'disabled' : '' }}>
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @elseif($application->status == 'Selection')
                                                <button class="btn btn-sm btn-success accept-btn"
                                                    data-application-id="{{ $application->id }}" title="Terima"
                                                    {{ $job->status == 'Closed' ? 'disabled' : '' }}>
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger reject-btn"
                                                    data-application-id="{{ $application->id }}" title="Tolak"
                                                    {{ $job->status == 'Closed' ? 'disabled' : '' }}>
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @endif

                                            <button class="btn btn-sm btn-outline-primary view-detail-btn"
                                                data-candidate-id="{{ $candidate ? $candidate->id : 0 }}"
                                                data-application-id="{{ $application->id }}" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="emptyState" class="text-center py-5" style="display: none;">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p class="text-muted mt-3">Tidak ada kandidat ditemukan</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ MODAL DETAIL KANDIDAT --}}
    <div class="modal fade" id="candidateDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-badge me-2"></i>Detail Kandidat
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="candidateDetailContent">
                        <!-- Content loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Data for AJAX --}}
    <div id="applicantsData" style="display: none;">
        @foreach ($job->applications as $application)
            @php
                $candidate = $application->candidate;
                $user = $candidate ? $candidate->user : null;
            @endphp
            @if ($candidate)
                <div class="applicant-data" data-id="{{ $candidate->id }}"
                    data-application-id="{{ $application->id }}">
                    <div class="data-json">
                        {!! json_encode([
                            'id' => $candidate->id,
                            'name' => $user->name ?? 'Unknown',
                            'email' => $user->email ?? '-',
                            'photo' => $user->photo ?? null,
                            'phone' => $candidate->phone_number ?? '-',
                            'gender' => $candidate->gender ?? '-',
                            'birth_date' => $candidate->birth_date ? \Carbon\Carbon::parse($candidate->birth_date)->format('d M Y') : '-',
                            'description' => $candidate->description ?? 'Tidak ada deskripsi',
                            'height' => $candidate->min_height ?? '-',
                            'weight' => $candidate->min_weight ?? '-',
                            'salary' => $candidate->min_salary ?? 0,
                            'english' => $candidate->level_english ?? '-',
                            'mandarin' => $candidate->level_mandarin ?? '-',
                            'avg_rating' => $candidate->avg_rating ?? 0,
                            'skills' => $candidate->skills->pluck('name')->toArray(),
                            'cities' => $candidate->preferredCities->pluck('name')->toArray(),
                            'industries' => $candidate->preferredIndustries->pluck('name')->toArray(),
                            'days' => $candidate->days->pluck('name')->toArray(),
                            'portfolios' => $candidate->portofolios->map(function ($p) {
                                    return [
                                        'file' => $p->file,
                                        'caption' => $p->caption ?? 'Portfolio',
                                    ];
                                })->toArray(),
                            'application_status' => $application->status,
                            'applied_date' => $application->created_at->format('d M Y H:i'),
                            'message' => $application->message ?? null,
                        ]) !!}
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchApplicant');
        const filterButtons = document.querySelectorAll('.filter-status');
        const applicantItems = document.querySelectorAll('.applicant-item');
        const emptyState = document.getElementById('emptyState');
        const hasInterview = {{ $job->has_interview ? 'true' : 'false' }};

        let currentFilter = 'all';

        // ========== FILTER BY STATUS ==========
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.status;
                filterApplicants();
            });
        });

        // ========== SEARCH FUNCTION ==========
        if (searchInput) {
            searchInput.addEventListener('input', filterApplicants);
        }

        function filterApplicants() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            let visibleCount = 0;

            applicantItems.forEach(item => {
                const status = item.dataset.status;
                const name = item.dataset.name || '';
                const email = item.dataset.email || '';

                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesFilter = currentFilter === 'all' || status === currentFilter;

                if (matchesSearch && matchesFilter) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        // ========== SHOW CANDIDATE DETAIL ==========
        function showCandidateDetail(candidateId, applicationId) {
            const dataElement = document.querySelector(`.applicant-data[data-id="${candidateId}"]`);
            if (!dataElement) {
                Swal.fire('Error', 'Data kandidat tidak ditemukan', 'error');
                return;
            }

            const candidateData = JSON.parse(dataElement.querySelector('.data-json').textContent);

            // ✅ Fetch rating & review data
            fetch(`/company/candidates/${candidateId}/rating-detail`)
                .then(response => response.json())
                .then(ratingData => {
                    if (!ratingData.success) {
                        throw new Error('Failed to load rating data');
                    }

                    const rating = ratingData.data;

                    // Build avatar HTML (sama seperti sebelumnya)
                    let avatarHTML = '';
                    if (candidateData.photo) {
                        avatarHTML = `
                    <img src="/storage/${candidateData.photo}"
                         alt="${candidateData.name}"
                         class="avatar-image-large mx-auto mb-3"
                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="avatar-circle-large mx-auto mb-3" style="display: none;">
                        ${candidateData.name.substring(0, 2).toUpperCase()}
                    </div>
                `;
                    } else {
                        avatarHTML = `
                    <div class="avatar-circle-large mx-auto mb-3">
                        ${candidateData.name.substring(0, 2).toUpperCase()}
                    </div>
                `;
                    }

                    // Badge status (sama seperti sebelumnya)
                    let statusBadge = '';
                    switch (candidateData.application_status) {
                        case 'Applied':
                        case 'Pending':
                            statusBadge = '<span class="badge badge-warning">Pending</span>';
                            break;
                        case 'Selection':
                            statusBadge = '<span class="badge badge-info">Seleksi</span>';
                            break;
                        case 'Accepted':
                            statusBadge = '<span class="badge badge-success">Diterima</span>';
                            break;
                        case 'Finished':
                            statusBadge = '<span class="badge badge-primary">Finished</span>';
                            break;
                        default:
                            statusBadge = '<span class="badge badge-danger">Ditolak</span>';
                    }

                    // Action buttons (sama seperti sebelumnya)
                    let actionButtons = '';
                    const isJobClosed = document.getElementById('allApplicantsModal').dataset.jobStatus ===
                        'Closed';

                    if (['Applied', 'Pending'].includes(candidateData.application_status)) {
                        if (hasInterview) {
                            actionButtons = `
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pesan untuk Kandidat (Opsional)</label>
                            <textarea id="application-message" class="form-control" rows="3" 
                                placeholder="Tulis pesan untuk kandidat..." maxlength="1000" 
                                ${isJobClosed ? 'disabled' : ''}></textarea>
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-info select-btn" data-application-id="${applicationId}" ${isJobClosed ? 'disabled' : ''}>
                                <i class="bi bi-check2-square me-2"></i>Pilih untuk Seleksi
                            </button>
                            <button class="btn btn-danger reject-btn" data-application-id="${applicationId}" ${isJobClosed ? 'disabled' : ''}>
                                <i class="bi bi-x-lg me-2"></i>Tolak Kandidat
                            </button>
                        </div>
                    `;
                        } else {
                            actionButtons = `
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pesan untuk Kandidat (Opsional)</label>
                            <textarea id="application-message" class="form-control" rows="3" 
                                placeholder="Tulis pesan untuk kandidat..." maxlength="1000" 
                                ${isJobClosed ? 'disabled' : ''}></textarea>
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-success accept-btn" data-application-id="${applicationId}" ${isJobClosed ? 'disabled' : ''}>
                                <i class="bi bi-check-lg me-2"></i>Terima Kandidat
                            </button>
                            <button class="btn btn-danger reject-btn" data-application-id="${applicationId}" ${isJobClosed ? 'disabled' : ''}>
                                <i class="bi bi-x-lg me-2"></i>Tolak Kandidat
                            </button>
                        </div>
                    `;
                        }
                    } else if (candidateData.application_status === 'Selection') {
                        actionButtons = `
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pesan untuk Kandidat (Opsional)</label>
                        <textarea id="application-message" class="form-control" rows="3" 
                            placeholder="Tulis pesan untuk kandidat..." maxlength="1000" 
                            ${isJobClosed ? 'disabled' : ''}></textarea>
                        <small class="text-muted">Maksimal 1000 karakter</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success accept-btn" data-application-id="${applicationId}" ${isJobClosed ? 'disabled' : ''}>
                            <i class="bi bi-check-lg me-2"></i>Terima Kandidat
                        </button>
                        <button class="btn btn-danger reject-btn" data-application-id="${applicationId}" ${isJobClosed ? 'disabled' : ''}>
                            <i class="bi bi-x-lg me-2"></i>Tolak Kandidat
                        </button>
                    </div>
                `;
                    }

                    if (isJobClosed && ['Applied', 'Pending', 'Selection'].includes(candidateData
                            .application_status)) {
                        actionButtons = `
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-lock me-2"></i>
                        <strong>Lowongan Ditutup</strong><br>
                        <small>Tidak dapat mengubah status lamaran karena lowongan sudah ditutup.</small>
                    </div>
                ` + actionButtons;
                    }

                    // ✅ GENERATE STARS HTML
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

                    // ✅ BUILD RATING TAB CONTENT
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

                    // ✅ BUILD MODAL CONTENT WITH TABS
                    const modalContent = `
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        ${avatarHTML}
                        <h4 class="fw-bold">${candidateData.name}</h4>
                        <p class="text-muted">${candidateData.email}</p>
                        <div class="mb-3">
                            ${statusBadge}
                        </div>
                        ${actionButtons}
                    </div>
                    <div class="col-md-8">
                        ${candidateData.message ? `
                            <div class="alert alert-warning mb-3">
                                <i class="bi bi-chat-left-text me-2"></i><strong>Pesan dari Perusahaan:</strong><br>
                                ${candidateData.message}
                            </div>
                        ` : ''}

                        <!-- ✅ TABS NAVIGATION -->
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

                        <!-- ✅ TABS CONTENT -->
                        <div class="tab-content">
                            <!-- PROFILE TAB -->
                            <div class="tab-pane fade show active" id="profileTab">
                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Pribadi</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%" class="fw-semibold"><i class="bi bi-telephone me-2"></i>Telepon</td>
                                            <td>${candidateData.phone}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold"><i class="bi bi-gender-ambiguous me-2"></i>Jenis Kelamin</td>
                                            <td>${candidateData.gender}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold"><i class="bi bi-calendar me-2"></i>Tanggal Lahir</td>
                                            <td>${candidateData.birth_date}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold"><i class="bi bi-rulers me-2"></i>Tinggi/Berat</td>
                                            <td>${candidateData.height} cm / ${candidateData.weight} kg</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold"><i class="bi bi-cash me-2"></i>Ekspektasi Gaji</td>
                                            <td>Rp ${parseInt(candidateData.salary).toLocaleString('id-ID')}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold"><i class="bi bi-star me-2"></i>Rating</td>
                                            <td>${rating.average_rating} / 5.0</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-translate me-2"></i>Kemampuan Bahasa</h5>
                                    <div class="d-flex gap-3">
                                        <span class="badge badge-info badge-lg">
                                            <i class="bi bi-flag me-1"></i>English: ${candidateData.english}
                                        </span>
                                        <span class="badge badge-info badge-lg">
                                            <i class="bi bi-flag me-1"></i>Mandarin: ${candidateData.mandarin}
                                        </span>
                                    </div>
                                </div>

                                ${candidateData.skills.length > 0 ? `
                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-tools me-2"></i>Keterampilan</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${candidateData.skills.map(skill => `<span class="badge badge-primary">${skill}</span>`).join('')}
                                    </div>
                                </div>
                                ` : ''}

                                ${candidateData.cities.length > 0 ? `
                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>Preferensi Lokasi</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${candidateData.cities.map(city => `<span class="badge badge-success">${city}</span>`).join('')}
                                    </div>
                                </div>
                                ` : ''}

                                ${candidateData.industries.length > 0 ? `
                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-briefcase me-2"></i>Industri Diminati</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${candidateData.industries.map(ind => `<span class="badge badge-warning">${ind}</span>`).join('')}
                                    </div>
                                </div>
                                ` : ''}

                                ${candidateData.days.length > 0 ? `
                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-calendar-week me-2"></i>Hari Kerja Preferensi</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${candidateData.days.map(day => `<span class="badge bg-secondary">${day}</span>`).join('')}
                                    </div>
                                </div>
                                ` : ''}

                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-file-text me-2"></i>Deskripsi</h5>
                                    <p class="text-muted">${candidateData.description}</p>
                                </div>

                                ${candidateData.portfolios.length > 0 ? `
                                <div class="detail-section mb-3">
                                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-folder me-2"></i>Portfolio (${candidateData.portfolios.length})</h5>
                                    <div class="row g-3">
                                        ${candidateData.portfolios.map(portfolio => `
                                            <div class="col-md-4">
                                                <div class="portfolio-card">
                                                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 3rem;"></i>
                                                    <p class="small mt-2 mb-2">${portfolio.caption}</p>
                                                    <a href="/storage/${portfolio.file}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </a>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                                ` : '<p class="text-muted"><i class="bi bi-inbox"></i> Tidak ada portfolio</p>'}

                                <div class="alert alert-info">
                                    <i class="bi bi-clock me-2"></i>Melamar pada: <strong>${candidateData.applied_date}</strong>
                                </div>
                            </div>

                            <!-- ✅ RATING TAB -->
                            <div class="tab-pane fade" id="ratingTab">
                                ${ratingTabContent}
                            </div>
                        </div>
                    </div>
                </div>
            `;

                    document.getElementById('candidateDetailContent').innerHTML = modalContent;

                    const detailModal = new bootstrap.Modal(document.getElementById(
                        'candidateDetailModal'));
                    detailModal.show();
                    attachActionButtons();
                })
                .catch(error => {
                    console.error('Error loading rating data:', error);
                    Swal.fire('Error', 'Gagal memuat data rating kandidat', 'error');
                });
        }

        // ========== SIDEBAR & MODAL CLICK HANDLERS ==========
        document.querySelectorAll('.view-detail-sidebar-btn, .view-detail-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const candidateId = this.dataset.candidateId;
                const applicationId = this.dataset.applicationId;

                if (!candidateId || candidateId == '0') {
                    Swal.fire('Error', 'Data kandidat tidak tersedia', 'error');
                    return;
                }

                const allApplicantsModalEl = document.getElementById('allApplicantsModal');
                const allApplicantsModal = bootstrap.Modal.getInstance(allApplicantsModalEl);

                if (allApplicantsModal) {
                    allApplicantsModalEl.addEventListener('hidden.bs.modal',
                        function showDetailModal() {
                            showCandidateDetail(candidateId, applicationId);
                            allApplicantsModalEl.removeEventListener('hidden.bs.modal',
                                showDetailModal);
                        }, {
                            once: true
                        });
                    allApplicantsModal.hide();
                } else {
                    showCandidateDetail(candidateId, applicationId);
                }
            });
        });

        // ========== ATTACH ACTION BUTTONS ==========
        function attachActionButtons() {
            document.querySelectorAll('.select-btn').forEach(button => {
                button.removeEventListener('click', handleSelect);
                button.addEventListener('click', handleSelect);
            });

            document.querySelectorAll('.accept-btn').forEach(button => {
                button.removeEventListener('click', handleAccept);
                button.addEventListener('click', handleAccept);
            });

            document.querySelectorAll('.reject-btn').forEach(button => {
                button.removeEventListener('click', handleReject);
                button.addEventListener('click', handleReject);
            });
        }

        // ✅ HANDLE SELECT FOR INTERVIEW
        function handleSelect(e) {
            const applicationId = this.dataset.applicationId;
            const message = document.getElementById('application-message')?.value || '';

            Swal.fire({
                title: 'Pilih untuk Seleksi?',
                text: 'Kandidat ini akan dipilih untuk tahap seleksi/interview',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#06b6d4',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Pilih!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateApplicationStatus(applicationId, 'Selection', message);
                }
            });
        }

        // ✅ HANDLE ACCEPT
        function handleAccept(e) {
            const applicationId = this.dataset.applicationId;
            const message = document.getElementById('application-message')?.value || '';

            Swal.fire({
                title: 'Terima Kandidat?',
                text: 'Kandidat ini akan diterima untuk posisi ini',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateApplicationStatus(applicationId, 'Accepted', message);
                }
            });
        }

        // ✅ HANDLE REJECT
        // ✅ HANDLE REJECT
        function handleReject(e) {
            const applicationId = this.dataset.applicationId;
            const message = document.getElementById('application-message')?.value || '';

            Swal.fire({
                title: 'Tolak Kandidat?',
                text: 'Kandidat ini akan ditolak untuk posisi ini',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateApplicationStatus(applicationId, 'Rejected', message);
                }
            });
        }

        // ✅ UPDATE APPLICATION STATUS
        function updateApplicationStatus(applicationId, status, message = '') {
            const candidateModal = bootstrap.Modal.getInstance(document.getElementById('candidateDetailModal'));
            if (candidateModal) {
                candidateModal.hide();
            }

            const applicantsModal = bootstrap.Modal.getInstance(document.getElementById('allApplicantsModal'));
            if (applicantsModal) {
                applicantsModal.hide();
            }

            fetch(`/company/applications/${applicationId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: status,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#14489b'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses',
                        confirmButtonColor: '#ef4444'
                    });
                });
        }

        // Initialize
        attachActionButtons();
    });
</script>
