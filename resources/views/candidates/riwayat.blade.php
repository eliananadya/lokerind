@extends('layouts.main')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--bg-blue) 0%, #e3f2fd 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">Riwayat</h1>
                    <p class="text-muted mb-0">Pantau semua aktivitas Anda termasuk laporan, feedback, dan status lamaran
                        pekerjaan</p>
                </div>
                <div class="col-md-4 text-md-end mt-md-0 mt-3">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                        <span class="badge bg-danger px-3 py-2">
                            <i class="bi bi-flag-fill me-1"></i>{{ $myReports->count() ?? 0 }} Reports
                        </span>
                        <span class="badge bg-warning px-3 py-2">
                            <i class="bi bi-star me-1"></i>{{ $feedbackApplicationsGivenByCandidate->count() }} Feedback
                            Saya
                        </span>
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-envelope-heart me-1"></i>{{ $totalInvitations ?? 0 }} Undangan
                        </span>
                        <span class="badge bg-info px-3 py-2">
                            <i class="bi bi-briefcase me-1"></i>{{ $applications->count() }} Lamaran
                        </span>
                        <span class="badge bg-dark px-3 py-2">
                            <i class="bi bi-shield-x me-1"></i>{{ $blockedCompanies->count() ?? 0 }} Blocked
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="border-bottom bg-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0">Filter Riwayat</h5>
                </div>
                <div class="col-md-6">
                    <div class="btn-group w-100" role="group" id="filterButtons">
                        <input type="radio" class="btn-check" name="historyFilter" id="filterApplications"
                            value="applications" checked autocomplete="off">
                        <label class="btn btn-outline-primary" for="filterApplications">
                            <i class="bi bi-briefcase me-1"></i>Lamaran
                        </label>

                        <input type="radio" class="btn-check" name="historyFilter" id="filterInvitations"
                            value="invitations" autocomplete="off">
                        <label class="btn btn-outline-primary" for="filterInvitations">
                            <i class="bi bi-envelope-heart me-1"></i>Undangan
                        </label>

                        <input type="radio" class="btn-check" name="historyFilter" id="filterMyFeedback"
                            value="my-feedback" autocomplete="off">
                        <label class="btn btn-outline-primary" for="filterMyFeedback">
                            <i class="bi bi-star me-1"></i>Feedback Saya
                        </label>

                        <input type="radio" class="btn-check" name="historyFilter" id="filterReports" value="reports"
                            autocomplete="off">
                        <label class="btn btn-outline-primary" for="filterReports">
                            <i class="bi bi-file-earmark-text me-1"></i>Reports
                        </label>

                        <input type="radio" class="btn-check" name="historyFilter" id="filterBlocked" value="blocked"
                            autocomplete="off">
                        <label class="btn btn-outline-primary" for="filterBlocked">
                            <i class="bi bi-shield-x me-1"></i>Blocked
                        </label>
                    </div>
                </div>
            </div>

            <!-- Filter Status - Only show for Applications tab -->
            <div class="row mt-3" id="status-filter-container">
                <div class="col-md-12">
                    <div class="d-flex align-items-center gap-3">
                        <label for="statusFilter" class="text-nowrap mb-0 fw-bold">
                            <i class="bi bi-funnel me-1"></i>Filter Status:
                        </label>
                        <select class="form-select" id="statusFilter" style="max-width: 250px;">
                            <option value="">Semua Status</option>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- History Content -->
    <section class="py-5">
        <div class="container">
            <div id="history-content">
                <!-- Application History -->
                <div class="history-section" id="applications-section">
                    @if ($applications->count() > 0)
                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">
                                <i class="bi bi-briefcase text-info me-2"></i>Status Lamaran Pekerjaan
                            </h4>
                            <div class="row g-3">
                                @foreach ($applications as $application)
                                    <div class="col-lg-6">
                                        <div class="card history-card rounded-3 h-100 border">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fw-bold mb-2">
                                                            {{ $application->jobPosting->title ?? 'Job Title' }}
                                                        </h5>
                                                        <p class="text-muted mb-1">
                                                            <i class="bi bi-building me-1"></i>
                                                            {{ $application->jobPosting->company->name ?? 'Company Name' }}
                                                        </p>
                                                        <p class="text-muted mb-0">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            {{ $application->jobPosting->city->name ?? 'Location' }}
                                                        </p>
                                                    </div>
                                                    <div class="ms-3">
                                                        @php
                                                            $statusColors = [
                                                                'Invited' => 'warning',
                                                                'Applied' => 'secondary',
                                                                'Reviewed' => 'info',
                                                                'Interview' => 'warning',
                                                                'Accepted' => 'success',
                                                                'Rejected' => 'danger',
                                                                'Withdrawn' => 'dark',
                                                            ];
                                                            $color = $statusColors[$application->status] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge bg-{{ $color }} px-3 py-2">
                                                            {{ $application->status }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="border-top pt-3">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">
                                                                @if ($application->status === 'Invited')
                                                                    Diundang Pada
                                                                @else
                                                                    Tanggal Melamar
                                                                @endif
                                                            </small>
                                                            <small class="fw-bold">
                                                                @if ($application->status === 'Invited' && $application->invited_at)
                                                                    {{ $application->invited_at->format('d M Y') }}
                                                                @else
                                                                    {{ $application->applied_at ? \Carbon\Carbon::parse($application->applied_at)->format('d M Y') : '-' }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Terakhir Update</small>
                                                            <small
                                                                class="fw-bold">{{ $application->updated_at->format('d M Y') }}</small>
                                                        </div>
                                                    </div>
                                                    @if ($application->message && trim($application->message) !== '')
                                                        @php
                                                            $messageStyle = match ($application->status) {
                                                                'Accepted' => [
                                                                    'bg' =>
                                                                        'background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);',
                                                                    'border' => 'border-left-color: #10b981;',
                                                                    'color' => 'color: #065f46;',
                                                                    'icon' => 'bi-check-circle-fill',
                                                                    'iconColor' => 'color: #10b981;',
                                                                ],
                                                                'Rejected' => [
                                                                    'bg' =>
                                                                        'background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);',
                                                                    'border' => 'border-left-color: #ef4444;',
                                                                    'color' => 'color: #991b1b;',
                                                                    'icon' => 'bi-x-circle-fill',
                                                                    'iconColor' => 'color: #ef4444;',
                                                                ],
                                                                'Selection' => [
                                                                    'bg' =>
                                                                        'background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);',
                                                                    'border' => 'border-left-color: #f59e0b;',
                                                                    'color' => 'color: #92400e;',
                                                                    'icon' => 'bi-star-fill',
                                                                    'iconColor' => 'color: #f59e0b;',
                                                                ],
                                                                'Interview' => [
                                                                    'bg' =>
                                                                        'background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);',
                                                                    'border' => 'border-left-color: #3b82f6;',
                                                                    'color' => 'color: #1e40af;',
                                                                    'icon' => 'bi-calendar-check-fill',
                                                                    'iconColor' => 'color: #3b82f6;',
                                                                ],
                                                                'Finished' => [
                                                                    'bg' =>
                                                                        'background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);',
                                                                    'border' => 'border-left-color: #6366f1;',
                                                                    'color' => 'color: #3730a3;',
                                                                    'icon' => 'bi-flag-fill',
                                                                    'iconColor' => 'color: #6366f1;',
                                                                ],
                                                                default => [
                                                                    'bg' =>
                                                                        'background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);',
                                                                    'border' => 'border-left-color: #6b7280;',
                                                                    'color' => 'color: #374151;',
                                                                    'icon' => 'bi-chat-left-text-fill',
                                                                    'iconColor' => 'color: #6b7280;',
                                                                ],
                                                            };
                                                        @endphp

                                                        <div class="mt-3 p-3 rounded-3 border-start border-4"
                                                            style="{{ $messageStyle['bg'] }} {{ $messageStyle['border'] }} box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                                            <h6 class="fw-bold mb-2 d-flex align-items-center gap-2"
                                                                style="{{ $messageStyle['color'] }} font-size: 0.875rem;">
                                                                <i class="bi {{ $messageStyle['icon'] }}"
                                                                    style="{{ $messageStyle['iconColor'] }} font-size: 1rem;"></i>
                                                                Pesan dari Perusahaan
                                                            </h6>
                                                            <p class="mb-0"
                                                                style="{{ $messageStyle['color'] }} line-height: 1.6; font-size: 0.875rem;">
                                                                {{ $application->message }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                    <div class="mt-3">
                                                        <!-- VIEW DETAIL BUTTON -->
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-sm w-100 mb-2 view-detail-btn"
                                                            data-application-id="{{ $application->id }}"
                                                            data-job-id="{{ $application->job_posting_id }}">
                                                            <i class="bi bi-eye me-1"></i>
                                                            Lihat Detail Lamaran
                                                        </button>

                                                        @if (in_array($application->status, ['Invited', 'Accepted']) && $application->invited_by_company)
                                                            <button type="button"
                                                                class="btn btn-success btn-sm w-100 mb-2 accept-invitation-btn"
                                                                data-application-id="{{ $application->id }}"
                                                                data-company-name="{{ $application->jobPosting->company->name ?? 'Company' }}"
                                                                data-job-title="{{ $application->jobPosting->title ?? 'Job' }}">
                                                                <i class="bi bi-check-circle me-1"></i>
                                                                Terima Undangan
                                                            </button>
                                                        @endif

                                                        @if (in_array($application->status, ['Applied', 'Reviewed', 'Selection', 'Accepted', 'Interview', 'Pending']))
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-sm w-100 withdraw-btn mb-2"
                                                                data-application-id="{{ $application->id }}"
                                                                data-company-name="{{ $application->jobPosting->company->name ?? 'Company' }}"
                                                                data-job-title="{{ $application->jobPosting->title ?? 'Job' }}"
                                                                data-current-status="{{ $application->status }}">
                                                                <i class="bi bi-x-circle me-1"></i>
                                                                @if ($application->status === 'Accepted')
                                                                    Tarik Lamaran (-5 Poin)
                                                                    <small class="d-block">(Slot akan dikembalikan)</small>
                                                                @else
                                                                    Tarik Lamaran
                                                                @endif
                                                            </button>
                                                        @endif

                                                        <!-- BLOCK COMPANY BUTTON -->
                                                        @php
                                                            $companyUserId =
                                                                $application->jobPosting->company->user_id ?? null;
                                                            $isBlocked = $companyUserId
                                                                ? \App\Models\Blacklist::where('user_id', Auth::id())
                                                                    ->where('blocked_user_id', $companyUserId)
                                                                    ->exists()
                                                                : false;
                                                        @endphp

                                                        @if ($application->status == 'Finished')
                                                            @if ($application->rating_company)
                                                                <button type="button"
                                                                    class="btn btn-success btn-sm w-100 mb-2 view-my-rating-btn"
                                                                    data-application-id="{{ $application->id }}"
                                                                    data-company-name="{{ $application->jobPosting->company->name ?? 'Company' }}"
                                                                    data-rating="{{ $application->rating_company }}"
                                                                    data-review="{{ $application->review_company ?? '' }}"
                                                                    data-feedbacks="{{ $application->feedbackApplications->where('given_by', 'candidate')->pluck('feedback.name')->implode(', ') }}">
                                                                    <i class="bi bi-eye me-1"></i>
                                                                    Lihat Rating
                                                                </button>
                                                            @else
                                                                <button type="button"
                                                                    class="btn btn-warning btn-sm w-100 rate-company-btn"
                                                                    data-application-id="{{ $application->id }}"
                                                                    data-company-name="{{ $application->jobPosting->company->name ?? 'Company' }}"
                                                                    data-job-title="{{ $application->jobPosting->title ?? 'Job' }}">
                                                                    <i class="bi bi-star-fill me-1"></i>
                                                                    Kasih Bintang
                                                                </button>
                                                            @endif
                                                        @endif

                                                        @if ($application->status === 'Withdrawn')
                                                            <div class="alert alert-dark py-2 mb-0">
                                                                <i class="bi bi-info-circle me-1"></i>
                                                                <small>Lamaran ditarik pada
                                                                    {{ $application->withdrawn_at->format('d M Y') }}</small>
                                                                @if ($application->withdraw_reason)
                                                                    <p class="mb-0 mt-1 small fst-italic">
                                                                        "{{ Str::limit($application->withdraw_reason, 100) }}"
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $applications->appends(['status' => request('status')])->links() }}
                            </div>
                        </div>
                    @else
                        <div class="py-5 text-center">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <h5 class="fw-bold mb-2 mt-3">Belum Ada Lamaran</h5>
                            <p class="text-muted">
                                @if (request('status'))
                                    Tidak ada lamaran dengan status "{{ ucfirst(request('status')) }}"
                                @else
                                    Anda belum melamar pekerjaan apapun
                                @endif
                            </p>
                            <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Cari Lowongan
                            </a>
                        </div>
                    @endif
                </div>
                {{-- ✅ INVITATIONS SECTION - FIXED --}}
                <div class="history-section" id="invitations-section" style="display: none;">
                    @if ($invitations->count() > 0)
                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">
                                <i class="bi bi-envelope-heart text-warning me-2"></i>Undangan dari Perusahaan
                            </h4>
                            <div class="row g-3">
                                @foreach ($invitations as $invitation)
                                    <div class="col-lg-6">
                                        <div
                                            class="card history-card rounded-3 h-100 border {{ $invitation->status === 'Invited' ? 'border-2' : '' }}">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <h5 class="fw-bold mb-0">
                                                                {{ $invitation->jobPosting->title ?? 'Job Title' }}
                                                            </h5>
                                                        </div>
                                                        <p class="text-muted mb-1">
                                                            <i class="bi bi-building me-1"></i>
                                                            {{ $invitation->jobPosting->company->name ?? 'Company Name' }}
                                                        </p>
                                                        <p class="text-muted mb-0">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            {{ $invitation->jobPosting->city->name ?? 'Location' }}
                                                        </p>
                                                    </div>
                                                    <div class="ms-3">
                                                        @php
                                                            $statusColors = [
                                                                'Invited' => 'warning',
                                                                'Selection' => 'info',
                                                                'Accepted' => 'success',
                                                                'Rejected' => 'danger',
                                                                'Interview' => 'info',
                                                            ];
                                                            $color = $statusColors[$invitation->status] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge bg-{{ $color }} px-3 py-2">
                                                            {{ $invitation->status }}
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Pesan dari Perusahaan (jika ada) --}}
                                                @if ($invitation->message && trim($invitation->message) !== '')
                                                    <div class="mb-3 p-3 rounded-3 border-start border-4 border-primary"
                                                        style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                                                        <h6 class="fw-bold mb-2 d-flex align-items-center gap-2 text-primary"
                                                            style="font-size: 0.875rem;">
                                                            <i class="bi bi-chat-left-text-fill"
                                                                style="font-size: 1rem;"></i>
                                                            Pesan dari Perusahaan
                                                        </h6>
                                                        <p class="mb-0 text-primary"
                                                            style="line-height: 1.6; font-size: 0.875rem;">
                                                            {{ $invitation->message }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <div class="border-top pt-3">
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Diundang Pada</small>
                                                            <small class="fw-bold">
                                                                {{ $invitation->invited_at ? \Carbon\Carbon::parse($invitation->invited_at)->format('d M Y') : '-' }}
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Terakhir Update</small>
                                                            <small
                                                                class="fw-bold">{{ $invitation->updated_at->format('d M Y') }}</small>
                                                        </div>
                                                    </div>

                                                    {{-- ✅ ACTION BUTTONS - DIPERBAIKI! --}}
                                                    <div class="mt-3">
                                                        <!-- VIEW DETAIL BUTTON -->
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-sm w-100 mb-2 view-invitation-detail-btn"
                                                            data-invitation-id="{{ $invitation->id }}"
                                                            data-job-id="{{ $invitation->job_posting_id }}">
                                                            <i class="bi bi-eye me-1"></i>
                                                            Lihat Detail Lowongan
                                                        </button>

                                                        {{-- ✅ TOMBOL ACCEPT & REJECT - PASTIKAN MUNCUL! --}}
                                                        @if ($invitation->status === 'Invited')
                                                            <div class="row g-2 mb-2">
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm w-100 accept-invitation-btn"
                                                                        data-invitation-id="{{ $invitation->id }}"
                                                                        data-company-name="{{ $invitation->jobPosting->company->name ?? 'Company' }}"
                                                                        data-job-title="{{ $invitation->jobPosting->title ?? 'Job' }}"
                                                                        data-has-interview="{{ $invitation->jobPosting->has_interview ?? 0 }}">
                                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                                        Terima
                                                                    </button>
                                                                </div>
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm w-100 reject-invitation-btn"
                                                                        data-invitation-id="{{ $invitation->id }}"
                                                                        data-company-name="{{ $invitation->jobPosting->company->name ?? 'Company' }}"
                                                                        data-job-title="{{ $invitation->jobPosting->title ?? 'Job' }}">
                                                                        <i class="bi bi-x-circle-fill me-1"></i>
                                                                        Tolak
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @elseif ($invitation->status === 'Accepted')
                                                            <div class="alert alert-success py-2 mb-0">
                                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                                <small><strong>Selamat!</strong> Anda telah diterima untuk
                                                                    posisi ini</small>
                                                            </div>
                                                        @elseif ($invitation->status === 'Selection')
                                                            <div class="alert alert-info py-2 mb-0">
                                                                <i class="bi bi-hourglass-split me-1"></i>
                                                                <small>Menunggu jadwal seleksi/wawancara dari
                                                                    perusahaan</small>
                                                            </div>
                                                        @elseif ($invitation->status === 'Rejected')
                                                            <div class="alert alert-danger py-2 mb-0">
                                                                <i class="bi bi-x-circle-fill me-1"></i>
                                                                <small>Anda telah menolak undangan ini</small>
                                                                @if ($invitation->reject_reason)
                                                                    <p class="mb-0 mt-2 small fst-italic">
                                                                        <i class="bi bi-chat-quote me-1"></i>
                                                                        "{{ Str::limit($invitation->reject_reason, 100) }}"
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        @elseif ($invitation->status === 'Interview')
                                                            <div class="alert alert-info py-2 mb-0">
                                                                <i class="bi bi-calendar-check me-1"></i>
                                                                <small>Menunggu jadwal wawancara dari perusahaan</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $invitations->links() }}
                            </div>
                        </div>
                    @else
                        <div class="py-5 text-center">
                            <i class="bi bi-envelope-open text-muted" style="font-size: 4rem;"></i>
                            <h5 class="fw-bold mb-2 mt-3">Belum Ada Undangan</h5>
                            <p class="text-muted">Anda belum menerima undangan dari perusahaan manapun</p>
                            <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Cari Lowongan
                            </a>
                        </div>
                    @endif
                </div>


            </div>
            <!-- MY FEEDBACK Section -->
            <div class="history-section" id="my-feedback-section" style="display: none;">
                <div class="card mb-4 border-warning">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-graph-up text-warning me-2"></i>Ringkasan Feedback & Rating Anda
                        </h5>

                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-2">
                                <i class="bi bi-star-fill text-warning me-2"></i>Average Rating
                            </h6>
                            <div class="d-flex align-items-center">
                                <div class="text-warning" style="font-size: 1.5rem;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="bi bi-star{{ $i <= round($feedbackSummary['average_rating']) ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <span
                                    class="fs-3 fw-bold ms-3">{{ number_format($feedbackSummary['average_rating'], 1) }}</span>
                                <span class="text-muted ms-2">/ 5.0</span>
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold text-muted mb-3">
                                <i class="bi bi-tags-fill text-info me-2"></i>Feedback dari Perusahaan
                            </h6>
                            <div class="row g-3">
                                @foreach ($feedbackSummary['feedback_counts'] as $fb)
                                    <div class="col-md-6">
                                        <div
                                            class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-tag-fill text-info me-2"></i>
                                                <span>{{ $fb['name'] }}</span>
                                            </div>
                                            <span
                                                class="badge {{ $fb['count'] > 0 ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                                {{ $fb['count'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @if ($feedbackApplicationsFromCompany->count() > 0)
                    <div class="mb-4">
                        <h4 class="fw-bold mb-3">
                            <i class="bi bi-star text-warning me-2"></i>Feedback dari Perusahaan
                        </h4>
                        <div class="row g-3">
                            @foreach ($feedbackApplicationsFromCompany as $item)
                                @php
                                    $application = $item->application;
                                    $feedbacks = $item->feedbacks; // ✅ Collection of feedbacks
                                    $job = $application->jobPosting ?? null;
                                    $company = $job->company ?? null;
                                    $companyUserId = $company->user_id ?? null;

                                    // ✅ Check if already reported
                                    $isReported = in_array($application->id, $reportedApplicationIds);

                                    // ✅ Check if already blocked
                                    $isBlocked = $companyUserId
                                        ? \App\Models\Blacklist::where('user_id', Auth::id())
                                            ->where('blocked_user_id', $companyUserId)
                                            ->exists()
                                        : false;
                                @endphp

                                <div class="col-lg-6">
                                    <div class="card history-card rounded-3 h-100 border">
                                        <div class="card-body p-4">
                                            {{-- Header Card --}}
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="bg-warning rounded-circle me-3 bg-opacity-10 p-3">
                                                    <i class="bi bi-star-fill text-warning fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">
                                                        {{ $job->title ?? 'Job Title' }}
                                                    </h6>
                                                    <p class="text-muted small mb-0">
                                                        <i
                                                            class="bi bi-building me-1"></i>{{ $company->name ?? 'Company' }}
                                                    </p>
                                                    <small class="text-muted">
                                                        {{ $item->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>

                                            {{-- ✅ SEMUA Feedback Tags --}}
                                            @if ($feedbacks->count() > 0)
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="bi bi-tag-fill me-1"></i>Feedback yang Diberikan
                                                        ({{ $feedbacks->count() }})
                                                        :
                                                    </small>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach ($feedbacks as $feedback)
                                                            @if ($feedback->feedback)
                                                                <span class="badge bg-warning text-dark px-3 py-2">
                                                                    <i
                                                                        class="bi bi-tag-fill me-1"></i>{{ $feedback->feedback->name }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Rating & Review dari Company --}}
                                            @if ($application->rating_candidates || $application->review_candidate)
                                                <div class="border-top pt-3 mb-3">
                                                    <h6 class="fw-bold mb-2 text-primary">
                                                        <i class="bi bi-star-fill me-1"></i>Rating & Review
                                                    </h6>

                                                    @if ($application->rating_candidates)
                                                        <div class="mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-muted small me-2">Rating:</span>
                                                                <div class="text-warning">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i
                                                                            class="bi bi-star{{ $i <= $application->rating_candidates ? '-fill' : '' }}"></i>
                                                                    @endfor
                                                                    <span
                                                                        class="text-dark ms-1 fw-bold">({{ $application->rating_candidates }}/5)</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($application->review_candidate)
                                                        <div
                                                            class="bg-light rounded p-3 border-start border-warning border-4">
                                                            <small class="text-muted d-block mb-1">
                                                                <i class="bi bi-chat-quote me-1"></i>Review:
                                                            </small>
                                                            <p class="small mb-0">
                                                                "{{ $application->review_candidate }}"</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Info Aplikasi --}}
                                            <div class="border-top pt-3 mb-3">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Status Lamaran:</small>
                                                        @php
                                                            $statusColors = [
                                                                'Applied' => 'secondary',
                                                                'Reviewed' => 'info',
                                                                'Interview' => 'warning',
                                                                'Accepted' => 'success',
                                                                'Rejected' => 'danger',
                                                                'Finished' => 'dark',
                                                            ];
                                                            $color = $statusColors[$application->status] ?? 'secondary';
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $color }}">{{ $application->status }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Tanggal Melamar:</small>
                                                        <small class="fw-bold">
                                                            {{ $application->applied_at ? \Carbon\Carbon::parse($application->applied_at)->format('d M Y') : '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Action Buttons --}}
                                            <div class="border-top pt-3">
                                                {{-- View Detail --}}
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm w-100 mb-2 view-detail-btn"
                                                    data-application-id="{{ $application->id }}"
                                                    data-job-id="{{ $application->job_posting_id }}">
                                                    <i class="bi bi-eye me-1"></i>Lihat Detail Lowongan
                                                </button>

                                                <div class="row g-2">
                                                    {{-- Report Company --}}
                                                    <div class="col-6">
                                                        @if (!$isReported)
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-sm w-100 report-company-btn-feedback"
                                                                data-application-id="{{ $application->id }}"
                                                                data-company-name="{{ $company->name ?? 'Company' }}"
                                                                data-job-title="{{ $job->title ?? 'Job' }}">
                                                                <i class="bi bi-flag-fill me-1"></i>Report
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-success btn-sm w-100"
                                                                disabled>
                                                                <i class="bi bi-check-circle me-1"></i>Dilaporkan
                                                            </button>
                                                        @endif
                                                    </div>

                                                    {{-- Block Company --}}
                                                    <div class="col-6">
                                                        @if (!$isBlocked && $companyUserId)
                                                            <button type="button"
                                                                class="btn btn-outline-dark btn-sm w-100 block-company-btn-feedback"
                                                                data-company-id="{{ $companyUserId }}"
                                                                data-company-name="{{ $company->name ?? 'Company' }}"
                                                                data-job-title="{{ $job->title ?? 'Job' }}">
                                                                <i class="bi bi-shield-x me-1"></i>Block
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-dark btn-sm w-100"
                                                                disabled>
                                                                <i class="bi bi-shield-check me-1"></i>Diblokir
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $feedbackApplicationsFromCompany->appends(['status' => request('status')])->links() }}
                        </div>
                    </div>
                @else
                    <div class="py-5 text-center">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h5 class="fw-bold mb-2 mt-3">Belum Ada Feedback</h5>
                        <p class="text-muted">
                            @if (request('status'))
                                Tidak ada feedback dari perusahaan untuk status "{{ ucfirst(request('status')) }}"
                            @else
                                Anda belum menerima feedback dari perusahaan manapun
                            @endif
                        </p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Cari Lowongan
                        </a>
                    </div>
                @endif
            </div>

            <!-- Reports History -->
            <div class="history-section" id="reports-section" style="display: none;">
                @if (isset($myReports) && $myReports->count() > 0)
                    <div class="mb-4">
                        <h4 class="fw-bold mb-3">
                            <i class="bi bi-flag-fill text-danger me-2"></i>Laporan yang Saya Kirim
                        </h4>
                        <div class="row g-3">
                            @foreach ($myReports as $report)
                                @php
                                    $application = $report->application;
                                    $job = $application->jobPosting ?? null;
                                @endphp

                                <div class="col-lg-6">
                                    <div class="card history-card rounded-3 h-100 border border-danger">
                                        <div class="card-body p-4">
                                            {{-- Header Card --}}
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="bg-danger rounded-circle me-3 bg-opacity-10 p-3">
                                                    <i class="bi bi-flag-fill text-danger fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">{{ $job->title ?? 'Job Title' }}
                                                            </h6>
                                                            <p class="text-muted small mb-0">
                                                                <i
                                                                    class="bi bi-building me-1"></i>{{ $job->company->name ?? 'Company' }}
                                                            </p>
                                                        </div>
                                                        @php
                                                            $statusColors = [
                                                                'pending' => 'warning',
                                                                'approved' => 'success',
                                                                'rejected' => 'danger',
                                                            ];
                                                            $statusColor =
                                                                $statusColors[$report->status] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge bg-{{ $statusColor }}">
                                                            {{ ucfirst($report->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Alasan Laporan --}}
                                            <div class="mb-3 bg-light rounded p-3 border-start border-danger border-4">
                                                <small class="text-muted d-block mb-2">
                                                    <i class="bi bi-chat-square-quote-fill text-danger me-1"></i>
                                                    <strong>Alasan Laporan:</strong>
                                                </small>
                                                <p class="small mb-0 fst-italic">"{{ $report->reason }}"</p>
                                            </div>

                                            {{-- Rating & Review dari Perusahaan --}}
                                            @if ($application->rating_candidates || $application->review_candidate)
                                                <div class="border-top pt-3 mb-3">
                                                    <h6 class="fw-bold mb-2 text-primary">
                                                        <i class="bi bi-star-fill me-1"></i>Rating & Review dari
                                                        Perusahaan
                                                    </h6>

                                                    @if ($application->rating_candidates)
                                                        <div class="mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-muted small me-2">Rating:</span>
                                                                <div class="text-warning">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i
                                                                            class="bi bi-star-fill{{ $i <= $application->rating_candidates ? '' : '-outline' }}"></i>
                                                                    @endfor
                                                                    <span
                                                                        class="text-dark ms-1 fw-bold">({{ $application->rating_candidates }}/5)</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($application->review_candidate)
                                                        <div class="bg-light rounded p-2">
                                                            <small class="text-muted d-block mb-1">
                                                                <i class="bi bi-chat-quote me-1"></i>Review:
                                                            </small>
                                                            <p class="small mb-0">
                                                                "{{ $application->review_candidate }}"</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Info Lowongan yang Dilaporkan --}}
                                            @if ($job)
                                                <div class="border-top pt-3">
                                                    <h6 class="fw-bold mb-3">
                                                        <i class="bi bi-briefcase me-1"></i>Detail Lowongan
                                                    </h6>

                                                    {{-- ✅ TAMBAHKAN: Company Name & Industry --}}
                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-start mb-2">
                                                            <i class="bi bi-building-fill text-primary me-2 mt-1"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Perusahaan:</small>
                                                                <p class="fw-bold mb-0">
                                                                    {{ $job->company->name ?? 'N/A' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-start">
                                                            <i class="bi bi-layers-fill text-success me-2 mt-1"></i>
                                                            <div>
                                                                <small class="text-muted d-block">Industri:</small>
                                                                <p class="fw-bold mb-0">
                                                                    {{ $job->industry->name ?? 'N/A' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Badges Type & Industry (Opsional - bisa dihapus karena sudah ada di atas) --}}
                                                    <div class="mb-2">
                                                        <span
                                                            class="badge bg-light text-dark me-1">{{ $job->typeJobs->name ?? 'N/A' }}</span>
                                                    </div>

                                                    {{-- Lokasi --}}
                                                    <p class="text-muted small mb-2">
                                                        <i class="bi bi-geo-alt me-1"></i>{{ $job->city->name ?? 'N/A' }}
                                                    </p>

                                                    {{-- Gaji --}}
                                                    <div class="mb-2">
                                                        <h5 class="fw-bold mb-1" style="color: var(--primary-blue);">
                                                            Rp {{ number_format($job->salary, 0, ',', '.') }}
                                                        </h5>
                                                        <span class="badge bg-primary" style="font-size: 0.7rem;">
                                                            <i class="bi bi-calendar-check me-1"></i>
                                                            {{ $job->type_salary == 'total' ? 'Total' : 'Per Hari' }}
                                                        </span>
                                                    </div>

                                                    {{-- Jadwal Kerja --}}
                                                    @if ($job->jobDatess && $job->jobDatess->count() > 0)
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block mb-1 fw-bold">
                                                                <i class="bi bi-calendar-event me-1"></i>Jadwal Kerja:
                                                            </small>
                                                            @foreach ($job->jobDatess->take(2) as $jobDate)
                                                                <small class="text-muted d-block ms-3">
                                                                    <i class="bi bi-dot"></i>
                                                                    {{ \Carbon\Carbon::parse($jobDate->date)->format('d M Y') }}
                                                                    @if ($jobDate->day)
                                                                        <span class="badge bg-info text-white ms-1"
                                                                            style="font-size: 0.6rem;">
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
                                                            @if ($job->jobDatess->count() > 2)
                                                                <small class="text-muted d-block ms-3 mt-1">
                                                                    <span class="badge bg-secondary"
                                                                        style="font-size: 0.6rem;">
                                                                        +{{ $job->jobDatess->count() - 2 }} jadwal
                                                                        lainnya
                                                                    </span>
                                                                </small>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    {{-- Slot --}}
                                                    <small class="text-muted d-block">
                                                        <i class="bi bi-people-fill me-1"></i>Slot:
                                                        {{ $job->slot }}
                                                    </small>
                                                </div>
                                            @endif

                                            {{-- Footer --}}
                                            <div class="border-top pt-3 mt-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i>Dilaporkan:
                                                        {{ $report->created_at->format('d M Y, H:i') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        ID: #{{ $report->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $myReports->links() }}
                        </div>
                    </div>
                @else
                    <div class="py-5 text-center">
                        <i class="bi bi-flag text-muted" style="font-size: 4rem;"></i>
                        <h5 class="fw-bold mb-2 mt-3">Belum Ada Laporan</h5>
                        <p class="text-muted">Anda belum pernah melaporkan perusahaan apapun</p>
                    </div>
                @endif
            </div>

            <!-- BLOCKED COMPANIES Section -->
            <div class="history-section" id="blocked-section" style="display: none;">
                @if (isset($blockedCompanies) && $blockedCompanies->count() > 0)
                    <div class="mb-4">
                        <h4 class="fw-bold mb-3">
                            <i class="bi bi-shield-x text-dark me-2"></i>Perusahaan yang Diblokir
                        </h4>
                        <div class="row g-3">
                            @foreach ($blockedCompanies as $blocked)
                                <div class="col-lg-6">
                                    <div class="card history-card rounded-3 h-100 border border-dark">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="bg-dark rounded-circle me-3 bg-opacity-10 p-3">
                                                    <i class="bi bi-shield-x text-dark fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">
                                                        {{ $blocked->blockedUser->company->name ?? 'Company Name' }}
                                                    </h6>
                                                    <p class="text-muted small mb-0">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Diblokir {{ $blocked->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if ($blocked->reason)
                                                <div class="mb-3 bg-light rounded p-3 border-start border-dark border-4">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="bi bi-chat-square-quote-fill text-dark me-1"></i>
                                                        <strong>Alasan Blokir:</strong>
                                                    </small>
                                                    <p class="small mb-0 fst-italic">"{{ $blocked->reason }}"</p>
                                                </div>
                                            @endif

                                            <div class="border-top pt-3">
                                                <button class="btn btn-success btn-sm w-100 unblock-company-btn"
                                                    data-blacklist-id="{{ $blocked->id }}"
                                                    data-company-id="{{ $blocked->blocked_user_id }}"
                                                    data-company-name="{{ $blocked->blockedUser->company->name ?? 'Company' }}">
                                                    <i class="bi bi-shield-check me-1"></i>Buka Blokir
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $blockedCompanies->links() }}
                        </div>
                    </div>
                @else
                    <div class="py-5 text-center">
                        <i class="bi bi-shield-check text-muted" style="font-size: 4rem;"></i>
                        <h5 class="fw-bold mb-2 mt-3">Tidak Ada Perusahaan yang Diblokir</h5>
                        <p class="text-muted">Anda belum memblokir perusahaan apapun</p>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </section>

    {{-- Modal Job Detail (copied from lowongan.blade.php) --}}
    <div class="modal fade" id="jobDetailModal" tabindex="-1" aria-labelledby="jobDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="jobDetailModalLabel">
                        <i class="bi bi-briefcase-fill me-2"></i>Detail Lamaran
                    </h5>
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
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4 text-center">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="informasi-lowongan-tab" data-bs-toggle="tab"
                                        href="#informasi-lowongan" role="tab">Informasi Lowongan</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="kualifikasi-tab" data-bs-toggle="tab" href="#kualifikasi"
                                        role="tab">Kualifikasi</a>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Withdraw Application --}}
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 bg-danger bg-opacity-10">
                    <h5 class="modal-title fw-bold text-danger" id="withdrawModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Tarik Lamaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- ✅ UBAH: Alert dinamis berdasarkan status --}}
                    <div class="alert alert-warning py-2 mb-3" id="withdraw-penalty-alert">
                        <i class="bi bi-info-circle me-1"></i>
                        <small id="withdraw-penalty-text">
                            <strong>Perhatian:</strong> Menarik lamaran akan mengurangi poin Anda sebanyak <strong>5
                                poin</strong>.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Saat Ini:</label>
                        <p class="mb-0" id="withdraw-current-status-display">
                            <span class="badge bg-secondary" id="withdraw-status-badge">-</span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Perusahaan:</label>
                        <p class="mb-0 fw-bold text-primary" id="withdraw-company-name"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Posisi:</label>
                        <p class="mb-0" id="withdraw-job-title"></p>
                    </div>

                    <div class="mb-3">
                        <label for="withdraw-reason" class="form-label fw-bold">
                            Alasan Menarik Lamaran <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="withdraw-reason" rows="4"
                            placeholder="Jelaskan alasan Anda menarik lamaran ini..." maxlength="500" required></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Minimal 10 karakter</small>
                            <small class="text-muted"><span id="withdraw-char-count">0</span>/500</small>
                        </div>
                    </div>

                    <input type="hidden" id="withdraw-application-id">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="submit-withdraw-btn">
                        <i class="bi bi-check-circle me-1"></i>Ya, Tarik Lamaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Report Company --}}
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="reportModalLabel">
                        <i class="bi bi-flag-fill text-danger me-2"></i>Laporkan Perusahaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning py-2 mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <small>Laporan akan ditinjau oleh tim kami. Berikan alasan yang jelas dan valid.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Perusahaan:</label>
                        <p class="mb-0 fw-bold text-primary" id="report-company-name"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Posisi:</label>
                        <p class="mb-0" id="report-job-title"></p>
                    </div>

                    <div class="mb-3">
                        <label for="report-reason" class="form-label fw-bold">
                            Alasan Laporan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="report-reason" rows="4"
                            placeholder="Jelaskan secara detail mengapa Anda melaporkan perusahaan ini..." maxlength="500"></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Minimal 10 karakter</small>
                            <small class="text-muted"><span id="char-count">0</span>/500</small>
                        </div>
                    </div>

                    <input type="hidden" id="report-application-id">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="submit-report-btn">
                        <i class="bi bi-send me-1"></i>Kirim Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .history-card {
            transition: all 0.3s ease;
        }

        .history-card:hover {
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

        .btn-check:checked+.btn-outline-primary {
            background-color: var(--primary-blue) !important;
            border-color: var(--primary-blue) !important;
            color: white !important;
        }

        .btn-check:focus+.btn-outline-primary,
        .btn-outline-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(20, 72, 155, 0.25);
        }

        .badge {
            font-weight: 500;
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .report-company-btn {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .report-company-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .btn-success:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }

        #report-reason-select {
            border: 2px solid #dee2e6;
        }

        #report-reason-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ DOM loaded - History page initialized');

            // ===== HELPER FUNCTIONS =====
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

            // ===== TAB FILTER =====
            const savedTab = localStorage.getItem('activeHistoryTab') || 'applications';
            console.log('💾 Saved tab:', savedTab);

            // Set radio button
            const activeRadio = document.querySelector(`input[name="historyFilter"][value="${savedTab}"]`);
            if (activeRadio) {
                activeRadio.checked = true;
            }

            // Show/hide sections
            document.querySelectorAll('.history-section').forEach(section => {
                section.style.display = 'none';
            });

            const activeSection = document.getElementById(`${savedTab}-section`);
            if (activeSection) {
                activeSection.style.display = 'block';
            }

            // Show/Hide Status Filter
            const statusFilterContainer = document.getElementById('status-filter-container');
            if (statusFilterContainer) {
                statusFilterContainer.style.display = savedTab === 'applications' ? 'block' : 'none';
            }

            // Tab change event
            document.querySelectorAll('input[name="historyFilter"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const filterType = this.value;
                    console.log('🔄 Tab changed to:', filterType);

                    localStorage.setItem('activeHistoryTab', filterType);

                    document.querySelectorAll('.history-section').forEach(section => {
                        section.style.display = 'none';
                    });

                    const targetSection = document.getElementById(`${filterType}-section`);
                    if (targetSection) {
                        targetSection.style.display = 'block';
                    }

                    // Show/Hide Status Filter
                    if (statusFilterContainer) {
                        statusFilterContainer.style.display = filterType === 'applications' ?
                            'block' : 'none';
                    }
                });
            });

            // ===== STATUS FILTER =====
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const status = this.value;
                    const currentUrl = new URL(window.location.href);

                    if (status) {
                        currentUrl.searchParams.set('status', status);
                    } else {
                        currentUrl.searchParams.delete('status');
                    }

                    currentUrl.searchParams.delete('apps_page');
                    currentUrl.searchParams.delete('reports_page');
                    currentUrl.searchParams.delete('myfeedback_page');

                    window.location.href = currentUrl.toString();
                });
            }

            // ===== CHARACTER COUNTER =====
            const reportReason = document.getElementById('report-reason');
            if (reportReason) {
                reportReason.addEventListener('input', function() {
                    const length = this.value.length;
                    const counter = document.getElementById('char-count');
                    if (counter) {
                        counter.textContent = length;

                        if (length < 10) {
                            counter.style.color = '#dc3545';
                        } else if (length < 50) {
                            counter.style.color = '#ffc107';
                        } else {
                            counter.style.color = '#28a745';
                        }
                    }
                });
            }

            const withdrawReason = document.getElementById('withdraw-reason');
            if (withdrawReason) {
                withdrawReason.addEventListener('input', function() {
                    const counter = document.getElementById('withdraw-char-count');
                    if (counter) {
                        counter.textContent = this.value.length;
                    }
                });
            }

            const rejectReason = document.getElementById('reject-invitation-reason');
            if (rejectReason) {
                rejectReason.addEventListener('input', function() {
                    const counter = document.getElementById('reject-invitation-char-count');
                    if (counter) {
                        counter.textContent = this.value.length;
                    }
                });
            }

            // ===== VIEW DETAIL LAMARAN =====
            document.querySelectorAll('.view-detail-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.dataset.jobId;
                    console.log('👁️ View detail clicked, job ID:', jobId);
                    loadJobModal(jobId);
                });
            });

            // ===== VIEW INVITATION DETAIL =====
            document.querySelectorAll('.view-invitation-detail-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.dataset.jobId;
                    console.log('👁️ View invitation detail clicked, job ID:', jobId);
                    loadJobModal(jobId);
                });
            });

            // ===== LOAD JOB MODAL =====
            function loadJobModal(jobId) {
                console.log('📡 Loading job modal for ID:', jobId);

                $.ajax({
                    url: '/jobs/' + jobId,
                    method: 'GET',
                    success: function(data) {
                        console.log('✅ Job data loaded:', data);
                        const job = data.job;

                        $('#job-title').text(job.title);
                        $('#company-name').text(job.company.name);
                        $('#updated-at').text(formatDate(job.updated_at));
                        $('#job-type-industry').html(`
                        <span class="badge bg-light text-dark me-2">${job.type_jobs?.name || 'N/A'}</span>
                        <span class="badge bg-light text-dark">${job.industry?.name || 'N/A'}</span>
                    `);
                        $('#job-location').html(
                            `<i class="bi bi-geo-alt me-2"></i>${job.city?.name || 'N/A'}`);

                        let salaryHTML = `
                        <div>
                            <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">
                                Rp ${formatNumber(job.salary)}
                            </h4>
                            <div class="mb-2">
                                <span class="badge bg-primary" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    ${job.type_salary === 'total' ? 'Total' : 'Per Hari'}
                                </span>
                            </div>
                    `;

                        if (job.close_recruitment) {
                            const closeDate = new Date(job.close_recruitment);
                            const now = new Date();
                            const daysLeft = Math.ceil((closeDate - now) / (1000 * 60 * 60 * 24));

                            salaryHTML += `
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-calendar-x me-1"></i>
                                <strong>Tutup:</strong>
                                <span class="${daysLeft <= 3 && daysLeft >= 0 ? 'text-danger fw-bold' : ''}">
                                    ${formatDate(job.close_recruitment)}
                                    ${daysLeft > 0 ? `(${daysLeft} hari lagi)` : daysLeft === 0 ? '<span class="badge bg-warning text-dark">Hari Terakhir!</span>' : '<span class="badge bg-danger">Sudah Ditutup</span>'}
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

                        salaryHTML += `
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                <strong>Jadwal Kerja:</strong>
                            </small>
                    `;

                        if (job.jobDatess && job.jobDatess.length > 0) {
                            job.jobDatess.forEach(function(jobDate) {
                                salaryHTML += `
                                <small class="text-muted d-block ms-3">
                                    <i class="bi bi-dot"></i>
                                    ${formatDate(jobDate.date)}
                                    ${jobDate.day ? `<span class="badge bg-info text-white ms-1" style="font-size: 0.65rem;">${jobDate.day.name}</span>` : ''}
                                    ${jobDate.start_time && jobDate.end_time ? `<span class="ms-1"><i class="bi bi-clock me-1"></i>${jobDate.start_time.substring(0,5)} - ${jobDate.end_time.substring(0,5)}</span>` : ''}
                                </small>
                            `;
                            });
                        } else {
                            salaryHTML +=
                                '<small class="text-muted d-block ms-3"><i class="bi bi-dot"></i>Tanggal belum ditentukan</small>';
                        }

                        salaryHTML += '</div></div>';
                        $('#salary-slot').html(salaryHTML);
                        $('#job-info-card').data('job-id', jobId);

                        $('#informasi-lowongan-content').html(`
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-bold"><i class="bi bi-geo-alt-fill me-2"></i>Alamat</h5>
                                <p>${job.address || 'Tidak tersedia'}</p>
                            </div>
                        </div>
                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <h5 class="fw-bold"><i class="bi bi-file-text-fill me-2"></i>Deskripsi</h5>
                                <div>${job.description || 'Tidak tersedia'}</div>
                            </div>
                        </div>
                    `);

                        $('#kualifikasi-content').html(`
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Persyaratan</h5>
                                <div class="row g-3">
                                    <div class="col-md-6"><strong>Bahasa English:</strong> ${job.level_english || 'N/A'}</div>
                                    <div class="col-md-6"><strong>Bahasa Mandarin:</strong> ${job.level_mandarin || 'N/A'}</div>
                                    <div class="col-md-6"><strong>Usia:</strong> ${job.min_age || 'N/A'} - ${job.max_age || 'N/A'} tahun</div>
                                    <div class="col-md-6"><strong>Tinggi:</strong> Min. ${job.min_height || 'N/A'} cm</div>
                                    <div class="col-md-6"><strong>Gender:</strong> ${job.gender || 'Semua'}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Skill yang Dibutuhkan</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    ${job.skills && job.skills.length > 0 
                                        ? job.skills.map(s => `<span class="badge bg-primary">${s.name}</span>`).join('') 
                                        : '<span class="text-muted">Tidak ada</span>'}
                                </div>
                            </div>
                        </div>
                    `);

                        $('#benefit-content').html(job.benefits && job.benefits.length > 0 ?
                            job.benefits.map(b => `
                            <div class="card shadow-sm mt-2">
                                <div class="card-body">
                                    <h6 class="fw-bold">${b.benefit?.name || 'Benefit'}</h6>
                                    <p class="mb-0">Jumlah: ${b.amount || 'N/A'}</p>
                                </div>
                            </div>
                        `).join('') :
                            '<p class="text-center text-muted">Tidak ada benefit</p>'
                        );

                        $('#jobDetailModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('❌ Error loading job:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat detail pekerjaan'
                        });
                    }
                });
            }

            // ===== ACCEPT INVITATION =====
            // ===== ACCEPT INVITATION - FIXED =====
            document.querySelectorAll('.accept-invitation-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const invitationId = this.dataset.invitationId || this.dataset
                        .applicationId;
                    const companyName = this.dataset.companyName;
                    const jobTitle = this.dataset.jobTitle;
                    const hasInterview = this.dataset.hasInterview;

                    console.log('✅ Accept invitation clicked:', {
                        invitationId,
                        companyName,
                        jobTitle,
                        hasInterview
                    });

                    const result = await Swal.fire({
                        title: 'Terima Undangan?',
                        html: `
                <div class="text-start">
                    <p><strong>Perusahaan:</strong> ${companyName}</p>
                    <p><strong>Posisi:</strong> ${jobTitle}</p>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        ${hasInterview == 1 
                            ? 'Anda akan masuk ke tahap <strong>Seleksi/Wawancara</strong>' 
                            : 'Anda akan langsung <strong>Diterima</strong> untuk posisi ini'}
                    </div>
                </div>
            `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-check-circle me-1"></i>Ya, Terima',
                        cancelButtonText: 'Tidak',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    });

                    if (!result.isConfirmed) return;

                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const response = await fetch(
                            `/applications/${invitationId}/accept-invitation`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                        const data = await response.json();
                        console.log('📥 Accept invitation response:', data);

                        if (response.ok && data.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#28a745'
                            });
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Gagal menerima undangan');
                        }
                    } catch (error) {
                        console.error('❌ Accept invitation error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });

            // ===== REJECT INVITATION - FIXED (Tanpa Alasan) =====
            document.querySelectorAll('.reject-invitation-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const invitationId = this.dataset.invitationId;
                    const companyName = this.dataset.companyName;
                    const jobTitle = this.dataset.jobTitle;

                    console.log('❌ Reject invitation clicked:', {
                        invitationId,
                        companyName,
                        jobTitle
                    });

                    const result = await Swal.fire({
                        title: 'Tolak Undangan?',
                        html: `
                <div class="text-start">
                    <p><strong>Perusahaan:</strong> ${companyName}</p>
                    <p><strong>Posisi:</strong> ${jobTitle}</p>
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Anda yakin ingin menolak undangan ini?
                    </div>
                </div>
            `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-x-circle me-1"></i>Ya, Tolak',
                        cancelButtonText: 'Tidak',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    });

                    if (!result.isConfirmed) return;

                    try {
                        const response = await fetch(
                            `/applications/${invitationId}/reject-invitation`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                        const data = await response.json();
                        console.log('📥 Reject invitation response:', data);

                        if (response.ok && data.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Undangan Ditolak',
                                text: data.message,
                                confirmButtonColor: '#28a745'
                            });
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Gagal menolak undangan');
                        }
                    } catch (error) {
                        console.error('❌ Reject invitation error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });

            // ===== WITHDRAW APPLICATION =====
            document.querySelectorAll('.withdraw-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const applicationId = this.dataset.applicationId;
                    const companyName = this.dataset.companyName;
                    const jobTitle = this.dataset.jobTitle;
                    const currentStatus = this.dataset.currentStatus;

                    console.log('🔙 Withdraw clicked:', {
                        applicationId,
                        companyName,
                        jobTitle,
                        currentStatus
                    });

                    // ✅ Set data ke modal
                    document.getElementById('withdraw-application-id').value = applicationId;
                    document.getElementById('withdraw-company-name').textContent = companyName;
                    document.getElementById('withdraw-job-title').textContent = jobTitle;
                    document.getElementById('withdraw-reason').value = '';

                    const counter = document.getElementById('withdraw-char-count');
                    if (counter) counter.textContent = '0';

                    // ✅ Update status badge
                    const statusBadge = document.getElementById('withdraw-status-badge');
                    const statusColors = {
                        'Invited': 'warning',
                        'Applied': 'secondary',
                        'Reviewed': 'info',
                        'Interview': 'warning',
                        'Accepted': 'success',
                        'Pending': 'secondary'
                    };

                    if (statusBadge) {
                        statusBadge.textContent = currentStatus;
                        statusBadge.className =
                            `badge bg-${statusColors[currentStatus] || 'secondary'}`;
                    }

                    // ✅ Update alert penalty text berdasarkan status
                    const penaltyAlert = document.getElementById('withdraw-penalty-alert');
                    const penaltyText = document.getElementById('withdraw-penalty-text');

                    if (currentStatus === 'Accepted') {
                        penaltyAlert.className = 'alert alert-danger py-2 mb-3';
                        penaltyText.innerHTML = `
                <strong>⚠️ Perhatian Penting!</strong><br>
                Status lamaran Anda saat ini: <strong>Accepted</strong><br>
                Menarik lamaran akan:
                <ul class="mb-0 mt-2">
                    <li><strong>Mengurangi 5 poin</strong> dari saldo poin Anda</li>
                    <li><strong>Slot akan dikembalikan</strong> ke perusahaan</li>
                </ul>
            `;
                    } else {
                        penaltyAlert.className = 'alert alert-info py-2 mb-3';
                        penaltyText.innerHTML = `
                <strong>ℹ️ Informasi:</strong><br>
                Status lamaran Anda saat ini: <strong>${currentStatus}</strong><br>
                Menarik lamaran <strong>TIDAK akan mengurangi poin</strong> Anda.
            `;
                    }

                    new bootstrap.Modal(document.getElementById('withdrawModal')).show();
                });
            });

            const submitWithdrawBtn = document.getElementById('submit-withdraw-btn');
            if (submitWithdrawBtn) {
                submitWithdrawBtn.addEventListener('click', async function() {
                    const applicationId = document.getElementById('withdraw-application-id').value;
                    const reason = document.getElementById('withdraw-reason').value.trim();

                    if (reason.length < 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: 'Alasan harus minimal 10 karakter',
                            confirmButtonColor: '#ffc107'
                        });
                        return;
                    }

                    const modalElement = document.getElementById('withdrawModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();

                    try {
                        console.log('📤 Withdrawing application:', {
                            applicationId,
                            reason
                        });

                        const response = await fetch(`/applications/${applicationId}/withdraw`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                reason: reason
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#28a745'
                            });

                            location.reload();
                        } else {
                            throw new Error(data.message || 'Gagal menarik lamaran');
                        }
                    } catch (error) {
                        console.error('❌ Withdraw error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
            document.querySelectorAll('.block-company-btn-feedback').forEach(button => {
                button.addEventListener('click', async function() {
                    const companyId = this.dataset.companyId;
                    const companyName = this.dataset.companyName;
                    const jobTitle = this.dataset.jobTitle || '';

                    console.log('🚫 Block company clicked from feedback:', {
                        companyId,
                        companyName,
                        jobTitle
                    });

                    if (!companyId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Company ID tidak ditemukan',
                            confirmButtonColor: '#dc3545'
                        });
                        return;
                    }

                    const {
                        value: formValues
                    } = await Swal.fire({
                        title: 'Blokir Perusahaan?',
                        html: `
                <div class="text-start">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian!</strong> Setelah diblokir, Anda tidak akan melihat lowongan dari perusahaan ini lagi.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Perusahaan:</label>
                        <p class="mb-2">${companyName}</p>
                    </div>
                    ${jobTitle ? `
                                                                                                                                                    <div class="mb-3">
                                                                                                                                                        <label class="form-label fw-bold">Dari Lowongan:</label>
                                                                                                                                                        <p class="mb-2">${jobTitle}</p>
                                                                                                                                                    </div>
                                                                                                                                                ` : ''}
                    <div class="mb-3">
                        <label for="block-reason-feedback" class="form-label fw-bold">
                            Alasan Memblokir <span class="text-danger">*</span>
                        </label>
                        <textarea
                            id="block-reason-feedback"
                            class="form-control"
                            placeholder="Jelaskan alasan Anda memblokir perusahaan ini..."
                            rows="4"
                            required
                        ></textarea>
                        <small class="text-muted">Minimal 10 karakter</small>
                    </div>
                </div>
            `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-shield-x me-1"></i> Ya, Blokir',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true,
                        width: '600px',
                        preConfirm: () => {
                            const reason = document.getElementById(
                                'block-reason-feedback').value.trim();

                            if (!reason) {
                                Swal.showValidationMessage('Alasan wajib diisi!');
                                return false;
                            }

                            if (reason.length < 10) {
                                Swal.showValidationMessage(
                                    'Alasan minimal 10 karakter!');
                                return false;
                            }

                            return {
                                reason: reason
                            };
                        }
                    });

                    if (!formValues) return;

                    try {
                        console.log('📤 Blocking company from feedback...', {
                            companyId,
                            reason: formValues.reason
                        });

                        const response = await fetch('{{ route('company.block') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                blocked_user_id: companyId,
                                reason: formValues.reason,
                                company_name: companyName,
                                job_title: jobTitle
                            })
                        });

                        const data = await response.json();
                        console.log('📥 Block response:', data);

                        if (response.ok && data.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Diblokir!',
                                html: `
                        <p>${data.message}</p>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Lowongan dari <strong>${companyName}</strong> tidak akan muncul lagi.</small>
                        </div>
                    `,
                                confirmButtonColor: '#28a745',
                                timer: 3000,
                                timerProgressBar: true
                            });

                            location.reload();
                        } else {
                            throw new Error(data.message || 'Gagal memblokir perusahaan');
                        }
                    } catch (error) {
                        console.error('❌ Block error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memblokir',
                            text: error.message ||
                                'Terjadi kesalahan saat memblokir perusahaan',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });
            // ===== REPORT COMPANY =====
            document.querySelectorAll('.report-company-btn, .report-company-btn-feedback').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const applicationId = this.dataset.applicationId;
                    const companyName = this.dataset.companyName;
                    const jobTitle = this.dataset.jobTitle;

                    console.log('🚩 Report button clicked:', {
                        applicationId,
                        companyName,
                        jobTitle
                    });

                    document.getElementById('report-application-id').value = applicationId;
                    document.getElementById('report-company-name').textContent = companyName;
                    document.getElementById('report-job-title').textContent = jobTitle;
                    document.getElementById('report-reason').value = '';

                    const counter = document.getElementById('char-count');
                    if (counter) counter.textContent = '0';

                    new bootstrap.Modal(document.getElementById('reportModal')).show();
                });
            });

            const submitReportBtn = document.getElementById('submit-report-btn');
            if (submitReportBtn) {
                submitReportBtn.addEventListener('click', function() {
                    const applicationId = document.getElementById('report-application-id').value;
                    const reason = document.getElementById('report-reason').value.trim();
                    const companyName = document.getElementById('report-company-name').textContent;

                    console.log('📤 Submitting report:', {
                        applicationId,
                        reason,
                        companyName
                    });

                    if (!reason || reason.length < 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Alasan Tidak Lengkap',
                            text: 'Mohon jelaskan alasan laporan Anda minimal 10 karakter.',
                            confirmButtonColor: '#ffc107'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Laporan',
                        html: `
                        <div class="text-start">
                            <p class="mb-2">Anda akan melaporkan:</p>
                            <p class="mb-1"><strong>Perusahaan:</strong> ${companyName}</p>
                            <p class="mb-3"><strong>Alasan:</strong> ${reason.substring(0, 100)}...</p>
                            <div class="alert alert-warning py-2 mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                <small>Laporan palsu dapat berakibat pada penangguhan akun Anda.</small>
                            </div>
                        </div>
                    `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-send me-1"></i>Ya, Kirim Laporan',
                        cancelButtonText: '<i class="bi bi-x-circle me-1"></i>Batal',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitReport(applicationId, reason);
                        }
                    });
                });
            }

            async function submitReport(applicationId, reason) {
                console.log('🔄 Sending report to server...', {
                    applicationId,
                    reason
                });

                try {
                    const response = await fetch('{{ route('report.company') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            application_id: applicationId,
                            reason: reason
                        })
                    });

                    console.log('📡 Response status:', response.status);
                    const data = await response.json();
                    console.log('📥 Response data:', data);

                    if (response.ok && data.success) {
                        const modalElement = document.getElementById('reportModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) modalInstance.hide();

                        document.querySelectorAll(`.report-company-btn[data-application-id="${applicationId}"]`)
                            .forEach(btn => {
                                btn.classList.remove('btn-outline-danger', 'report-company-btn');
                                btn.classList.add('btn-success');
                                btn.disabled = true;
                                btn.title = 'Laporan Anda sudah diterima';
                                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Sudah Dilaporkan';
                            });

                        Swal.fire({
                            icon: 'success',
                            title: 'Laporan Terkirim!',
                            html: `
                            <div class="text-start">
                                <p class="mb-2">${data.message}</p>
                                <div class="alert alert-info py-2 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small><strong>ID Laporan:</strong> #${data.report.id}</small><br>
                                    <small><strong>Status:</strong> ${data.report.status}</small>
                                </div>
                            </div>
                        `,
                            confirmButtonColor: '#28a745'
                        });
                    } else {
                        throw new Error(data.message || 'Gagal mengirim laporan');
                    }
                } catch (error) {
                    console.error('❌ Report error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message,
                        confirmButtonColor: '#dc3545'
                    });
                }
            }

            // ===== BLOCK COMPANY =====
            document.querySelectorAll('.block-company-btn, .block-company-btn-application').forEach(button => {
                button.addEventListener('click', async function() {
                    const companyId = this.dataset.companyId;
                    const companyName = this.dataset.companyName;
                    const jobTitle = this.dataset.jobTitle || '';

                    console.log('🚫 Block company clicked:', {
                        companyId,
                        companyName,
                        jobTitle
                    });

                    if (!companyId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Company ID tidak ditemukan',
                            confirmButtonColor: '#dc3545'
                        });
                        return;
                    }

                    const {
                        value: formValues
                    } = await Swal.fire({
                        title: 'Blokir Perusahaan?',
                        html: `
                        <div class="text-start">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Perhatian!</strong> Setelah diblokir, Anda tidak akan melihat lowongan dari perusahaan ini lagi.
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Perusahaan:</label>
                                <p class="mb-2">${companyName}</p>
                            </div>
                            ${jobTitle ? `
                                                                                                                                                                                                                                                                <div class="mb-3">
                                                                                                                                                                                                                                                                    <label class="form-label fw-bold">Dari Lowongan:</label>
                                                                                                                                                                                                                                                                    <p class="mb-2">${jobTitle}</p>
                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                            ` : ''}
                            <div class="mb-3">
                                <label for="block-reason" class="form-label fw-bold">
                                    Alasan Memblokir <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    id="block-reason"
                                    class="form-control"
                                    placeholder="Jelaskan alasan Anda memblokir perusahaan ini..."
                                    rows="4"
                                    required
                                ></textarea>
                                <small class="text-muted">Minimal 10 karakter</small>
                            </div>
                        </div>
                    `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-shield-x me-1"></i> Ya, Blokir',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true,
                        width: '600px',
                        preConfirm: () => {
                            const reason = document.getElementById('block-reason')
                                .value.trim();

                            if (!reason) {
                                Swal.showValidationMessage('Alasan wajib diisi!');
                                return false;
                            }

                            if (reason.length < 10) {
                                Swal.showValidationMessage(
                                    'Alasan minimal 10 karakter!');
                                return false;
                            }

                            return {
                                reason: reason
                            };
                        }
                    });

                    if (!formValues) return;

                    try {
                        console.log('📤 Blocking company...', {
                            companyId,
                            reason: formValues.reason
                        });

                        const response = await fetch('{{ route('company.block') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                blocked_user_id: companyId,
                                reason: formValues.reason,
                                company_name: companyName,
                                job_title: jobTitle
                            })
                        });

                        const data = await response.json();
                        console.log('📥 Block response:', data);

                        if (response.ok && data.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Diblokir!',
                                html: `
                                <p>${data.message}</p>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <small>Lowongan dari <strong>${companyName}</strong> tidak akan muncul lagi.</small>
                                </div>
                            `,
                                confirmButtonColor: '#28a745',
                                timer: 3000,
                                timerProgressBar: true
                            });

                            location.reload();
                        } else {
                            throw new Error(data.message || 'Gagal memblokir perusahaan');
                        }
                    } catch (error) {
                        console.error('❌ Block error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memblokir',
                            text: error.message ||
                                'Terjadi kesalahan saat memblokir perusahaan',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });

            // ===== UNBLOCK COMPANY =====
            document.querySelectorAll('.unblock-company-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const blacklistId = this.dataset.blacklistId;
                    const companyId = this.dataset.companyId;
                    const companyName = this.dataset.companyName;

                    console.log('✅ Unblock company clicked:', {
                        blacklistId,
                        companyId,
                        companyName
                    });

                    if (!blacklistId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Blacklist ID tidak ditemukan',
                            confirmButtonColor: '#dc3545'
                        });
                        return;
                    }

                    const result = await Swal.fire({
                        title: 'Buka Blokir Perusahaan?',
                        html: `
                        <div class="text-start">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Setelah dibuka blokirnya, Anda akan kembali melihat lowongan dari perusahaan ini.
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Perusahaan:</label>
                                <p class="mb-0">${companyName}</p>
                            </div>
                        </div>
                    `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-shield-check me-1"></i> Ya, Buka Blokir',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true,
                        width: '500px'
                    });

                    if (!result.isConfirmed) return;

                    try {
                        console.log('📤 Unblocking company...', {
                            blacklistId,
                            companyId
                        });

                        const response = await fetch('{{ route('company.unblock') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                blacklist_id: blacklistId,
                                company_id: companyId
                            })
                        });

                        const data = await response.json();
                        console.log('📥 Unblock response:', data);

                        if (response.ok && data.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Dibuka!',
                                html: `
                                <p>${data.message}</p>
                                <div class="alert alert-success mt-3">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <small>Anda kembali dapat melihat lowongan dari <strong>${companyName}</strong>.</small>
                                </div>
                            `,
                                confirmButtonColor: '#28a745',
                                timer: 3000,
                                timerProgressBar: true
                            });

                            location.reload();
                        } else {
                            throw new Error(data.message || 'Gagal membuka blokir');
                        }
                    } catch (error) {
                        console.error('❌ Unblock error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Membuka Blokir',
                            text: error.message ||
                                'Terjadi kesalahan saat membuka blokir',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });

            console.log('✅ All event listeners attached successfully');
        });
        // ===== REJECT INVITATION - UPDATED =====
        document.querySelectorAll('.reject-invitation-btn').forEach(button => {
            button.addEventListener('click', function() {
                const invitationId = this.dataset.invitationId;
                const companyName = this.dataset.companyName;
                const jobTitle = this.dataset.jobTitle;

                console.log('❌ Reject invitation clicked:', {
                    invitationId,
                    companyName,
                    jobTitle
                });

                document.getElementById('reject-invitation-id').value = invitationId;
                document.getElementById('reject-invitation-company-name').textContent = companyName;
                document.getElementById('reject-invitation-job-title').textContent = jobTitle;
                document.getElementById('reject-invitation-reason').value = '';

                const counter = document.getElementById('reject-invitation-char-count');
                if (counter) counter.textContent = '0';

                new bootstrap.Modal(document.getElementById('rejectInvitationModal')).show();
            });
        });

        const submitRejectBtn = document.getElementById('submit-reject-invitation-btn');
        if (submitRejectBtn) {
            submitRejectBtn.addEventListener('click', async function() {
                const invitationId = document.getElementById('reject-invitation-id').value;
                const reason = document.getElementById('reject-invitation-reason').value.trim();

                // Validasi opsional - reason boleh kosong
                if (reason.length > 0 && reason.length < 10) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Jika diisi, alasan harus minimal 10 karakter',
                        confirmButtonColor: '#ffc107'
                    });
                    return;
                }

                // Tutup modal
                const modalElement = document.getElementById('rejectInvitationModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) modalInstance.hide();

                // Show loading
                Swal.fire({
                    title: 'Memproses...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch(`/applications/${invitationId}/reject-invitation`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            reason: reason
                        })
                    });

                    const data = await response.json();
                    console.log('📥 Reject invitation response:', data);

                    if (response.ok && data.success) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Undangan Ditolak',
                            html: `
                        <div class="text-start">
                            <p>${data.message}</p>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Undangan dari <strong>${data.data.company_name}</strong> telah ditolak.</small>
                            </div>
                        </div>
                    `,
                            confirmButtonColor: '#28a745',
                            timer: 3000,
                            timerProgressBar: true
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Gagal menolak undangan');
                    }
                } catch (error) {
                    console.error('❌ Reject invitation error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message,
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }
        // ✅ RATE COMPANY - Kasih Bintang
        document.querySelectorAll('.rate-company-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const applicationId = this.dataset.applicationId;
                const companyName = this.dataset.companyName;
                const jobTitle = this.dataset.jobTitle;

                const {
                    value: formValues
                } = await Swal.fire({
                    title: 'Beri Rating & Review',
                    html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Perusahaan:</label>
                        <p class="mb-0">${companyName}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Posisi:</label>
                        <p class="mb-0">${jobTitle}</p>
                    </div>
                    <div class="mb-3">
                        <label for="rating-company" class="form-label fw-bold">
                            Rating <span class="text-danger">*</span>
                        </label>
                        <div class="star-rating" id="starRatingCompany">
                            ${[1,2,3,4,5].map(star => `
                                                                                                                                                                                                        <i class="bi bi-star star-icon-company" data-rating="${star}" 
                                                                                                                                                                                                           style="font-size: 2rem; cursor: pointer; color: #d1d5db;"></i>
                                                                                                                                                                                                    `).join('')}
                        </div>
                        <input type="hidden" id="ratingCompanyValue" value="0">
                    </div>
                    <div class="mb-3">
                        <label for="review-company" class="form-label fw-bold">Review (Opsional)</label>
                        <textarea id="review-company" class="form-control" rows="4" 
                                  placeholder="Tulis pengalaman Anda bekerja di perusahaan ini..." 
                                  maxlength="1000"></textarea>
                        <small class="text-muted">Maksimal 1000 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label for="feedbackCompanySelect" class="form-label fw-bold">Feedback (Opsional)</label>
                        <select id="feedbackCompanySelect" class="form-control" multiple="multiple" style="width: 100%;">
                            @foreach ($feedbacks->where('for', 'company') as $feedback)
                                <option value="{{ $feedback->id }}">{{ $feedback->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih satu atau lebih feedback</small>
                    </div>
                </div>
            `,
                    width: '600px',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-send me-2"></i>Kirim Rating',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    didOpen: () => {
                        // Initialize Select2
                        $('#feedbackCompanySelect').select2({
                            placeholder: 'Pilih feedback...',
                            allowClear: true,
                            closeOnSelect: false,
                            dropdownParent: $('.swal2-popup'),
                            language: {
                                noResults: () => "Tidak ada hasil ditemukan",
                                searching: () => "Mencari..."
                            }
                        });

                        // Star rating functionality
                        const stars = document.querySelectorAll('.star-icon-company');
                        const ratingInput = document.getElementById('ratingCompanyValue');

                        stars.forEach(star => {
                            star.addEventListener('click', function() {
                                const rating = this.dataset.rating;
                                ratingInput.value = rating;

                                stars.forEach((s, index) => {
                                    if (index < rating) {
                                        s.classList.remove(
                                            'bi-star');
                                        s.classList.add(
                                            'bi-star-fill');
                                        s.style.color = '#ffc107';
                                    } else {
                                        s.classList.remove(
                                            'bi-star-fill');
                                        s.classList.add('bi-star');
                                        s.style.color = '#d1d5db';
                                    }
                                });
                            });

                            star.addEventListener('mouseenter', function() {
                                const rating = this.dataset.rating;
                                stars.forEach((s, index) => {
                                    if (index < rating) {
                                        s.style.color = '#ffc107';
                                    }
                                });
                            });

                            star.addEventListener('mouseleave', function() {
                                const currentRating = ratingInput.value;
                                stars.forEach((s, index) => {
                                    if (index >= currentRating) {
                                        s.style.color = '#d1d5db';
                                    }
                                });
                            });
                        });
                    },
                    didClose: () => {
                        if ($('#feedbackCompanySelect').data('select2')) {
                            $('#feedbackCompanySelect').select2('destroy');
                        }
                    },
                    preConfirm: () => {
                        const rating = document.getElementById('ratingCompanyValue').value;
                        const review = document.getElementById('review-company').value
                            .trim();
                        const feedbacks = $('#feedbackCompanySelect').val() || [];

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
                try {
                    const response = await fetch(`/applications/${applicationId}/rate-company`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            rating_company: formValues.rating,
                            review_company: formValues.review,
                            feedbacks: formValues.feedbacks
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        let html = `<p>${data.message}</p>`;

                        if (data.data.point_reward > 0) {
                            html += `
                        <div class="alert alert-success mt-3">
                            <strong>Poin Anda:</strong> ${data.data.old_point} → ${data.data.new_point}
                            <br><small>Reward: +${data.data.point_reward} poin</small>
                        </div>
                    `;
                        }

                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            html: html,
                            confirmButtonColor: '#28a745'
                        });

                        location.reload();
                    } else {
                        throw new Error(data.message || 'Gagal mengirim rating');
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

        // VIEW MY RATING - Lihat Rating yang Sudah Diberikan
        document.querySelectorAll('.view-my-rating-btn').forEach(button => {
            button.addEventListener('click', function() {
                const companyName = this.dataset.companyName;
                const rating = this.dataset.rating;
                const review = this.dataset.review;
                const feedbacks = this.dataset.feedbacks;

                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml +=
                            '<i class="bi bi-star-fill" style="color: #ffc107; font-size: 1.5rem;"></i> ';
                    } else {
                        starsHtml +=
                            '<i class="bi bi-star" style="color: #d1d5db; font-size: 1.5rem;"></i> ';
                    }
                }

                Swal.fire({
                    title: 'Rating Anda',
                    html: `
                <div class="text-start">
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.875rem;">
                            <i class="bi bi-building me-2"></i>Perusahaan
                        </label>
                        <p class="mb-0 fs-5 fw-semibold">${companyName}</p>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.875rem;">
                            <i class="bi bi-star-fill me-2"></i>Rating yang Anda Berikan
                        </label>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <div>${starsHtml}</div>
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">${rating}/5</span>
                        </div>
                    </div>

                    ${review ? `
                                                                                                                                                                                                <div class="mb-3 pb-3 border-bottom">
                                                                                                                                                                                                    <label class="form-label fw-bold text-muted" style="font-size: 0.875rem;">
                                                                                                                                                                                                        <i class="bi bi-chat-quote-fill me-2"></i>Review Anda
                                                                                                                                                                                                    </label>
                                                                                                                                                                                                    <div class="p-3 bg-light rounded mt-2">
                                                                                                                                                                                                        <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">${review}</p>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            ` : ''}

                    ${feedbacks ? `
                                                                                                                                                                                                <div class="mb-3">
                                                                                                                                                                                                    <label class="form-label fw-bold text-muted" style="font-size: 0.875rem;">
                                                                                                                                                                                                        <i class="bi bi-tags-fill me-2"></i>Feedback yang Dipilih
                                                                                                                                                                                                    </label>
                                                                                                                                                                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                                                                                                                                                                        ${feedbacks.split(', ').map(fb => `
                                    <span class="badge bg-warning px-3 py-2" style="font-size: 0.875rem;">
                                        <i class="bi bi-tag-fill me-1"></i>${fb}
                                    </span>
                                `).join('')}
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            ` : `
                                                                                                                                                                                                <div class="mb-3">
                                                                                                                                                                                                    <label class="form-label fw-bold text-muted" style="font-size: 0.875rem;">
                                                                                                                                                                                                        <i class="bi bi-tags-fill me-2"></i>Feedback yang Dipilih
                                                                                                                                                                                                    </label>
                                                                                                                                                                                                    <p class="text-muted mb-0 mt-2">
                                                                                                                                                                                                        <i class="bi bi-info-circle me-2"></i>Tidak ada feedback yang dipilih
                                                                                                                                                                                                    </p>
                                                                                                                                                                                                </div>
                                                                                                                                                                                            `}
                </div>
            `,
                    width: '600px',
                    confirmButtonText: '<i class="bi bi-x-circle me-2"></i>Tutup',
                    confirmButtonColor: '#6c757d'
                });
            });
        });
    </script>
@endsection
