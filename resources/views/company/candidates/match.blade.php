@extends('layouts.main')

@section('title', 'Kandidat yang Cocok')

{{-- ✅ SELECT2 CDN --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    :root {
        --primary-blue: #14489b;
        --secondary-blue: #244770;
        --dark-blue: #1e3992;
        --light-blue: #dbeafe;
        --bg-blue: #eff6ff;
        --excellent: #10b981;
        --good: #3b82f6;
        --fair: #f59e0b;
    }

    body {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
    }

    /* ===== HEADER ===== */
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

    /* ===== STATS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        border-left: 5px solid;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.excellent {
        border-left-color: var(--excellent);
    }

    .stat-card.good {
        border-left-color: var(--good);
    }

    .stat-card.fair {
        border-left-color: var(--fair);
    }

    .stat-card.total {
        border-left-color: var(--primary-blue);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.8;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ===== JOB SELECTOR ===== */
    .job-selector-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    /* ✅ SELECT2 CUSTOM STYLING */
    .select2-container--default .select2-selection--single {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.5rem;
        height: auto;
        min-height: 50px;
        transition: all 0.3s;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(20, 72, 155, 0.1);
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827;
        line-height: 1.5;
        padding-left: 0.5rem;
        font-size: 1.05rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 10px;
    }

    .select2-dropdown {
        border: 2px solid var(--primary-blue);
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary-blue);
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.5rem;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: var(--primary-blue);
        outline: none;
    }

    /* ===== CANDIDATE CARD ===== */
    .candidate-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .candidate-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-blue), var(--dark-blue));
        transform: scaleX(0);
        transition: transform 0.3s;
    }

    .candidate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(20, 72, 155, 0.15);
    }

    .candidate-card:hover::before {
        transform: scaleX(1);
    }

    .candidate-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(20, 72, 155, 0.3);
        margin: 0 auto 1rem;
    }

    .candidate-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .candidate-email {
        color: #6b7280;
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 1rem;
    }

    /* ===== MATCH SCORE ===== */
    .match-score-container {
        text-align: center;
        margin: 1.5rem 0;
        padding: 1rem;
        background: var(--bg-blue);
        border-radius: 10px;
    }

    .match-score {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .match-score.excellent {
        color: var(--excellent);
    }

    .match-score.good {
        color: var(--good);
    }

    .match-score.fair {
        color: var(--fair);
    }

    .match-label {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }

    .match-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .match-badge.excellent {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .match-badge.good {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .match-badge.fair {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    /* ===== SKILLS & INFO ===== */
    .info-section {
        margin: 1rem 0;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .skill-tag {
        display: inline-block;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0.25rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: #4b5563;
    }

    .info-item i {
        color: var(--primary-blue);
    }

    /* ===== BUTTONS ===== */
    .btn-invite {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-invite:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(20, 72, 155, 0.3);
        color: white;
    }

    .btn-view-profile {
        background: white;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .btn-view-profile:hover {
        background: var(--bg-blue);
        color: var(--dark-blue);
        transform: translateY(-2px);
    }

    /* ===== MODAL DETAIL ===== */
    .modal-header-custom {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .swal2-popup {
        border-radius: 15px !important;
    }

    .detail-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .detail-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #6b7280;
    }

    .detail-value {
        color: #111827;
        font-weight: 500;
        text-align: right;
    }

    .portfolio-item {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s;
    }

    .portfolio-item:hover {
        border-color: var(--primary-blue);
        box-shadow: 0 4px 12px rgba(20, 72, 155, 0.1);
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

    .portfolio-link:hover {
        text-decoration: underline;
    }

    /* ===== EMPTY STATE ===== */
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

    /* ===== LOADING ===== */
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
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
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .candidate-card {
        animation: fadeInUp 0.5s ease-out;
    }

    .candidate-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .candidate-card:nth-child(2) {
        animation-delay: 0.1s;
    }

    .candidate-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .candidate-card:nth-child(4) {
        animation-delay: 0.2s;
    }
</style>

@section('content')
    <div class="container py-4">
        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div class="container">
                <h1 class="page-title">
                    <i class="bi bi-people-fill me-3"></i>
                    Kandidat yang Cocok
                </h1>
                <p class="page-subtitle">
                    Temukan kandidat terbaik yang sesuai dengan lowongan Anda menggunakan AI Matching
                </p>
            </div>
        </div>

        {{-- STATISTICS CARDS --}}
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">{{ $stats['total_matches'] }}</div>
                <div class="stat-label">Total Kandidat Cocok</div>
            </div>

            <div class="stat-card excellent">
                <div class="stat-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stat-value">{{ $stats['excellent_matches'] }}</div>
                <div class="stat-label">Sangat Cocok (80%+)</div>
            </div>

            <div class="stat-card good">
                <div class="stat-icon">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </div>
                <div class="stat-value">{{ $stats['good_matches'] }}</div>
                <div class="stat-label">Cocok (60-79%)</div>
            </div>

            <div class="stat-card fair">
                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-value">{{ $stats['fair_matches'] }}</div>
                <div class="stat-label">Cukup Cocok (30-59%)</div>
            </div>
        </div>

        {{-- JOB SELECTOR --}}
        <div class="job-selector-card">
            <label class="form-label fw-bold mb-3">
                <i class="bi bi-briefcase me-2"></i>Pilih Lowongan:
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

        {{-- CANDIDATE CARDS --}}
        @if ($selectedJob)
            @if ($matchingCandidates->count() > 0)
                <div class="row g-4">
                    @foreach ($matchingCandidates as $candidate)
                        <div class="col-md-6 col-lg-4">
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
                                        <span>{{ $candidate->gender }}</span>
                                    </div>
                                    @if ($candidate->min_salary)
                                        <div class="info-item">
                                            <i class="bi bi-cash"></i>
                                            <strong>Gaji Min:</strong>
                                            <span>Rp {{ number_format($candidate->min_salary, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="info-item">
                                        <i class="bi bi-translate"></i>
                                        <strong>English:</strong>
                                        <span>{{ $candidate->level_english }}</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-chat-dots"></i>
                                        <strong>Mandarin:</strong>
                                        <span>{{ $candidate->level_mandarin }}</span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <button class="btn btn-invite btn-invite-candidate"
                                    data-candidate-id="{{ $candidate->id }}" data-candidate-name="{{ $candidate->name }}"
                                    data-job-id="{{ $selectedJob->id }}">
                                    <i class="bi bi-send"></i>
                                    Undang Kandidat
                                </button>

                                <button class="btn btn-view-profile" onclick="viewCandidateDetail({{ $candidate->id }})">
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

    {{-- LOADING OVERLAY --}}
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>
@endsection

{{-- ✅ LOAD SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // ✅ INITIALIZE SELECT2
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

        // ===== JOB SELECTOR CHANGE =====
        $('#jobSelector').on('change', function() {
            const jobId = $(this).val();
            if (jobId) {
                $('#loadingOverlay').addClass('active');
                window.location.href = `{{ route('company.candidates.match') }}?job_id=${jobId}`;
            }
        });


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

            if (message === undefined) return; // Cancelled

            // Show loading
            Swal.fire({
                title: 'Mengirim Undangan...',
                html: '<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            // Send AJAX request
            try {
                // ✅ GUNAKAN URL LANGSUNG (lebih aman)
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
        // Show loading
        Swal.fire({
            title: 'Memuat Detail...',
            html: '<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        try {
            console.log('=== START viewCandidateDetail ===');
            console.log('Candidate ID:', candidateId);

            const url = `{{ url('candidates') }}/${candidateId}/detail`;
            console.log('Fetching URL:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            console.log('Response status:', response.status);

            const responseText = await response.text();
            console.log('Response text:', responseText.substring(0, 200) + '...');

            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                throw new Error('Response bukan JSON valid');
            }

            console.log('Result keys:', Object.keys(result));

            if (!response.ok) {
                throw new Error(result.message || `HTTP error! status: ${response.status}`);
            }

            if (!result.success) {
                throw new Error(result.message || 'Gagal memuat data kandidat');
            }

            // ✅ FIX: Support both 'data' and 'candidate' keys
            const candidate = result.data || result.candidate;

            if (!candidate) {
                console.error('Candidate not found in response!');
                console.error('Available keys:', Object.keys(result));
                throw new Error('Data kandidat tidak ditemukan dalam response');
            }

            console.log('Candidate loaded:', candidate.name);

            // ✅ Build skills HTML
            let skillsHtml = '';
            if (candidate.skills && Array.isArray(candidate.skills) && candidate.skills.length > 0) {
                skillsHtml = candidate.skills.map(skill =>
                    `<span class="skill-tag">${skill.name || 'N/A'}</span>`
                ).join('');
            } else {
                skillsHtml = '<p class="text-muted mb-0">Tidak ada skills</p>';
            }

            // ✅ Build preferred industries HTML
            let industriesHtml = '';
            if (candidate.preffered_industries && Array.isArray(candidate.preffered_industries) && candidate
                .preffered_industries.length > 0) {
                // Fetch industry names if needed
                industriesHtml = '<p class="text-muted mb-0">Data industri tersedia (perlu mapping)</p>';
            } else {
                industriesHtml = '<p class="text-muted mb-0">Tidak ada preferensi industri</p>';
            }

            // ✅ Build preferred cities HTML
            let citiesHtml = '';
            if (candidate.preffered_cities && Array.isArray(candidate.preffered_cities) && candidate
                .preffered_cities.length > 0) {
                citiesHtml = '<p class="text-muted mb-0">Data kota tersedia (perlu mapping)</p>';
            } else {
                citiesHtml = '<p class="text-muted mb-0">Tidak ada preferensi kota</p>';
            }

            // ✅ Build preferred type jobs HTML
            let typeJobsHtml = '';
            if (candidate.preferred_type_jobs && Array.isArray(candidate.preferred_type_jobs) && candidate
                .preferred_type_jobs.length > 0) {
                typeJobsHtml = candidate.preferred_type_jobs.map(type =>
                    `<span class="skill-tag">${type.name || 'N/A'}</span>`
                ).join('');
            } else {
                typeJobsHtml = '<p class="text-muted mb-0">Tidak ada preferensi tipe pekerjaan</p>';
            }

            // ✅ Build portfolio HTML
            let portfolioHtml = '';
            if (candidate.portofolios && Array.isArray(candidate.portofolios) && candidate.portofolios.length > 0) {
                portfolioHtml = candidate.portofolios.map(portfolio => {
                    // ✅ Build URL dengan JavaScript
                    const fileUrl = portfolio.file ? `/storage/${portfolio.file}` : null;

                    return `
            <div class="portfolio-item">
                <div class="portfolio-title">${portfolio.caption || 'Portfolio'}</div>
                ${portfolio.caption ? `<div class="portfolio-desc">${portfolio.caption}</div>` : ''}
                ${fileUrl ? `
                    <a href="${fileUrl}" target="_blank" class="portfolio-link">
                        <i class="bi bi-link-45deg"></i> Lihat Portfolio
                    </a>
                ` : '<p class="text-muted mb-0 mt-2">File tidak tersedia</p>'}
            </div>
        `;
                }).join('');
            } else {
                portfolioHtml = '<p class="text-muted mb-0">Belum ada portfolio</p>';
            }


            console.log('=== END viewCandidateDetail (Success) ===');

            // Show detail modal
            Swal.fire({
                title: `<div class="modal-header-custom p-3">
                        <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>${candidate.name || 'Kandidat'}</h4>
                    </div>`,
                html: `
                <div class="text-start" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Personal Info -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-person-badge"></i>
                            Informasi Pribadi
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">${candidate.user?.email || '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Gender:</span>
                            <span class="detail-value">${candidate.gender || '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tanggal Lahir:</span>
                            <span class="detail-value">${candidate.birth_date || '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">No. Telepon:</span>
                            <span class="detail-value">${candidate.phone_number || '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tinggi Badan:</span>
                            <span class="detail-value">${candidate.min_height ? candidate.min_height + ' cm' : '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Berat Badan:</span>
                            <span class="detail-value">${candidate.min_weight ? candidate.min_weight + ' kg' : '-'}</span>
                        </div>
                    </div>

                    <!-- Language Skills -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-translate"></i>
                            Kemampuan Bahasa
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">English:</span>
                            <span class="detail-value">${candidate.level_english || '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mandarin:</span>
                            <span class="detail-value">${candidate.level_mandarin || '-'}</span>
                        </div>
                    </div>

                    <!-- Salary & Rating -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-cash-stack"></i>
                            Gaji & Rating
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Gaji Minimum:</span>
                            <span class="detail-value">${candidate.min_salary ? 'Rp ' + new Intl.NumberFormat('id-ID').format(candidate.min_salary) : '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Rating:</span>
                            <span class="detail-value">${candidate.avg_rating ? candidate.avg_rating + '/5 ⭐' : 'Belum ada rating'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Point:</span>
                            <span class="detail-value">${candidate.point || 0}</span>
                        </div>
                    </div>

                    <!-- Skills -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-tools"></i>
                            Skills
                        </div>
                        ${skillsHtml}
                    </div>

                    <!-- Preferred Type Jobs -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-briefcase"></i>
                            Tipe Pekerjaan yang Diminati
                        </div>
                        ${typeJobsHtml}
                    </div>

                    <!-- Description -->
                    ${candidate.description ? `
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-card-text"></i>
                            Deskripsi
                        </div>
                        <p class="mb-0">${candidate.description}</p>
                    </div>
                    ` : ''}

                    <!-- Portfolio -->
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="bi bi-folder"></i>
                            Portfolio
                        </div>
                        ${portfolioHtml}
                    </div>
                </div>
            `,
                width: '800px',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'candidate-detail-modal'
                }
            });

        } catch (error) {
            console.error('=== ERROR in viewCandidateDetail ===');
            console.error('Error:', error);

            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: error.message || 'Terjadi kesalahan saat memuat detail kandidat',
                confirmButtonColor: '#dc3545'
            });
        }
    }
</script>
