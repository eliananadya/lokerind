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
                                                                'invited' => 'warning',
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

                                                @if ($application->status === 'invited' && $application->invited_by_company)
                                                    <div class="alert alert-warning mb-3">
                                                        <i class="bi bi-envelope-heart me-1"></i>
                                                        <strong>Undangan dari Perusahaan!</strong>
                                                        <p class="mb-0 mt-1 small">
                                                            Anda diundang untuk melamar posisi ini.
                                                            Terima undangan untuk mendapat +15 poin!
                                                        </p>
                                                    </div>
                                                @endif

                                                <div class="border-top pt-3">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">
                                                                @if ($application->status === 'invited')
                                                                    Diundang Pada
                                                                @else
                                                                    Tanggal Melamar
                                                                @endif
                                                            </small>
                                                            <small class="fw-bold">
                                                                @if ($application->status === 'invited' && $application->invited_at)
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

                                                    <div class="mt-3">
                                                        <!-- VIEW DETAIL BUTTON -->
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-sm w-100 mb-2 view-detail-btn"
                                                            data-application-id="{{ $application->id }}"
                                                            data-job-id="{{ $application->job_posting_id }}">
                                                            <i class="bi bi-eye me-1"></i>
                                                            Lihat Detail Lamaran
                                                        </button>

                                                        @if (in_array($application->status, ['invited', 'Accepted']) && $application->invited_by_company)
                                                            <button type="button"
                                                                class="btn btn-success btn-sm w-100 mb-2 accept-invitation-btn"
                                                                data-application-id="{{ $application->id }}"
                                                                data-company-name="{{ $application->jobPosting->company->name ?? 'Company' }}"
                                                                data-job-title="{{ $application->jobPosting->title ?? 'Job' }}">
                                                                <i class="bi bi-check-circle me-1"></i>
                                                                Terima Undangan (+10 Poin)
                                                            </button>
                                                        @endif

                                                        @if (in_array($application->status, ['Applied', 'Reviewed', 'invited', 'Accepted', 'Interview', 'Pending']))
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

                                                        @if (!$isBlocked)
                                                            <button type="button"
                                                                class="btn btn-outline-dark btn-sm w-100 mb-2 block-company-btn-application"
                                                                data-company-id="{{ $companyUserId }}"
                                                                data-company-name="{{ $application->jobPosting->company->name ?? 'Company' }}"
                                                                data-job-title="{{ $application->jobPosting->title ?? 'Job' }}">
                                                                <i class="bi bi-shield-x me-1"></i>
                                                                Blokir Perusahaan
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-dark btn-sm w-100 mb-2"
                                                                disabled>
                                                                <i class="bi bi-shield-check me-1"></i>
                                                                Sudah Diblokir
                                                            </button>
                                                        @endif

                                                        @if ($application->status == 'Finished')
                                                            <div class="alert alert-success py-2 mb-2">
                                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                                <small>Proses lamaran selesai</small>
                                                            </div>
                                                        @endif

                                                        @if ($application->status == 'Finished')
                                                            @if ($application->rating_company)
                                                                <div class="alert alert-success py-2 mb-0">
                                                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                                                    <small>Anda sudah memberikan rating
                                                                        {{ $application->rating_company }}/5</small>
                                                                </div>
                                                            @else
                                                                <button type="button"
                                                                    class="btn btn-warning btn-sm w-100"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#ratingModal{{ $application->id }}">
                                                                    <i class="bi bi-star-fill me-1"></i> Kasih Bintang (+10
                                                                    Poin)
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
                <!-- ✅ ADD THIS SECTION - INVITATIONS (Company Invitations) -->
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
                                            class="card history-card rounded-3 h-100 border {{ $invitation->status === 'invited' ? 'border-warning border-2' : '' }}">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            @if ($invitation->status === 'invited')
                                                                <span class="badge bg-warning text-dark me-2">
                                                                    <i class="bi bi-envelope-heart me-1"></i>BARU!
                                                                </span>
                                                            @endif
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
                                                                'invited' => 'warning',
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

                                                @if ($invitation->status === 'invited')
                                                    <div class="alert alert-warning mb-3">
                                                        <i class="bi bi-envelope-heart me-1"></i>
                                                        <strong>Anda Diundang!</strong>
                                                        <p class="mb-0 mt-1 small">
                                                            Perusahaan tertarik dengan profil Anda.
                                                            @if ($invitation->jobPosting->has_interview == 1)
                                                                Jika diterima, Anda akan masuk tahap wawancara.
                                                            @else
                                                                Terima undangan untuk langsung diterima!
                                                            @endif
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

                                                    <div class="mt-3">
                                                        <!-- VIEW DETAIL BUTTON -->
                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-sm w-100 mb-2 view-invitation-detail-btn"
                                                            data-invitation-id="{{ $invitation->id }}"
                                                            data-job-id="{{ $invitation->job_posting_id }}">
                                                            <i class="bi bi-eye me-1"></i>
                                                            Lihat Detail Lowongan
                                                        </button>

                                                        @if ($invitation->status === 'invited')
                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm w-100 accept-invitation-action-btn"
                                                                        data-invitation-id="{{ $invitation->id }}"
                                                                        data-company-name="{{ $invitation->jobPosting->company->name ?? 'Company' }}"
                                                                        data-job-title="{{ $invitation->jobPosting->title ?? 'Job' }}"
                                                                        data-has-interview="{{ $invitation->jobPosting->has_interview }}">
                                                                        <i class="bi bi-check-circle me-1"></i>
                                                                        Terima
                                                                    </button>
                                                                </div>
                                                                <div class="col-6">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm w-100 reject-invitation-btn"
                                                                        data-invitation-id="{{ $invitation->id }}"
                                                                        data-company-name="{{ $invitation->jobPosting->company->name ?? 'Company' }}"
                                                                        data-job-title="{{ $invitation->jobPosting->title ?? 'Job' }}">
                                                                        <i class="bi bi-x-circle me-1"></i>
                                                                        Tolak
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @elseif ($invitation->status === 'Accepted')
                                                            <div class="alert alert-success py-2 mb-0">
                                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                                <small>Anda telah menerima undangan ini</small>
                                                            </div>
                                                        @elseif ($invitation->status === 'Rejected')
                                                            <div class="alert alert-danger py-2 mb-0">
                                                                <i class="bi bi-x-circle-fill me-1"></i>
                                                                <small>Anda telah menolak undangan ini</small>
                                                                @if ($invitation->reject_reason)
                                                                    <p class="mb-0 mt-1 small fst-italic">
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

                {{-- ✅ ADD THIS MODAL - Reject Invitation Modal --}}
                <div class="modal fade" id="rejectInvitationModal" tabindex="-1"
                    aria-labelledby="rejectInvitationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0 bg-danger bg-opacity-10">
                                <h5 class="modal-title fw-bold text-danger" id="rejectInvitationModalLabel">
                                    <i class="bi bi-x-circle me-2"></i>Tolak Undangan
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning py-2 mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small>Anda akan menolak undangan dari perusahaan ini.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Perusahaan:</label>
                                    <p class="mb-0 fw-bold text-primary" id="reject-invitation-company-name"></p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Posisi:</label>
                                    <p class="mb-0" id="reject-invitation-job-title"></p>
                                </div>

                                <div class="mb-3">
                                    <label for="reject-invitation-reason" class="form-label fw-bold">
                                        Alasan Menolak (Opsional)
                                    </label>
                                    <textarea class="form-control" id="reject-invitation-reason" rows="4"
                                        placeholder="Jelaskan alasan Anda menolak undangan ini..." maxlength="500"></textarea>
                                    <div class="d-flex justify-content-end mt-1">
                                        <small class="text-muted"><span
                                                id="reject-invitation-char-count">0</span>/500</small>
                                    </div>
                                </div>

                                <input type="hidden" id="reject-invitation-id">
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-arrow-left me-1"></i>Batal
                                </button>
                                <button type="button" class="btn btn-danger" id="submit-reject-invitation-btn">
                                    <i class="bi bi-x-circle me-1"></i>Ya, Tolak Undangan
                                </button>
                            </div>
                        </div>
                    </div>
                </div> <!-- MY FEEDBACK Section -->
                <div class="history-section" id="my-feedback-section" style="display: none;">
                    @if ($feedbackApplicationsGivenByCandidate->count() > 0)
                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">
                                <i class="bi bi-star text-warning me-2"></i>Feedback yang Saya Berikan
                            </h4>
                            <div class="row g-3">
                                @foreach ($feedbackApplicationsGivenByCandidate as $myFeedback)
                                    <div class="col-lg-6">
                                        <div class="card history-card rounded-3 h-100 border">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="bg-warning rounded-circle me-3 bg-opacity-10 p-3">
                                                        <i class="bi bi-star-fill text-warning fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold mb-1">
                                                            {{ $myFeedback->application->jobPosting->title ?? 'Job Title' }}
                                                        </h6>
                                                        <p class="text-muted small mb-0">
                                                            <i
                                                                class="bi bi-building me-1"></i>{{ $myFeedback->application->jobPosting->company->name ?? 'Company' }}
                                                        </p>
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $myFeedback->created_at->diffForHumans() }}
                                                    </small>
                                                </div>

                                                @if ($myFeedback->application->rating_company)
                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted small me-2">Rating Anda:</span>
                                                            <div class="text-warning">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $myFeedback->application->rating_company)
                                                                        <i class="bi bi-star-fill"></i>
                                                                    @else
                                                                        <i class="bi bi-star"></i>
                                                                    @endif
                                                                @endfor
                                                                <span
                                                                    class="text-dark ms-1">({{ $myFeedback->application->rating_company }}/5)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($myFeedback->application->review_company)
                                                    <div class="bg-light mb-3 rounded p-3">
                                                        <small class="text-muted d-block mb-2">
                                                            <i class="bi bi-chat-quote me-1"></i>Review Anda:
                                                        </small>
                                                        <p class="small mb-0">
                                                            {{ $myFeedback->application->review_company }}</p>
                                                    </div>
                                                @endif

                                                @if ($myFeedback->feedback)
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block mb-2">Feedback yang
                                                            Diberikan:</small>
                                                        <span class="badge bg-info text-white">
                                                            <i
                                                                class="bi bi-tag-fill me-1"></i>{{ $myFeedback->feedback->name }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <div
                                                    class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                    <small class="text-muted">
                                                        <i
                                                            class="bi bi-calendar3 me-1"></i>{{ $myFeedback->created_at->format('d F Y, H:i') }}
                                                    </small>
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="bi bi-person me-1"></i>Anda
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $feedbackApplicationsGivenByCandidate->appends(['status' => request('status')])->links() }}
                            </div>
                        </div>
                    @else
                        <div class="py-5 text-center">
                            <i class="bi bi-star text-muted" style="font-size: 4rem;"></i>
                            <h5 class="fw-bold mb-2 mt-3">Belum Ada Feedback</h5>
                            <p class="text-muted">Anda belum memberikan feedback apapun</p>
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
                                                            <i
                                                                class="bi bi-geo-alt me-1"></i>{{ $job->city->name ?? 'N/A' }}
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
                                                    <div
                                                        class="mb-3 bg-light rounded p-3 border-start border-dark border-4">
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
                        <label for="report-reason-select" class="form-label fw-bold">
                            Kategori Laporan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="report-reason-select">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Review tidak sesuai">Review tidak sesuai dengan fakta</option>
                            <option value="Konten tidak pantas">Konten tidak pantas atau menyinggung</option>
                            <option value="Informasi palsu">Informasi palsu atau menyesatkan</option>
                            <option value="Spam">Spam atau promosi tidak relevan</option>
                            <option value="Diskriminasi">Diskriminasi (SARA, gender, dll)</option>
                            <option value="Pelecehan">Pelecehan atau intimidasi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="report-reason" class="form-label fw-bold">
                            Detail Laporan <span class="text-danger">*</span>
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
            document.querySelectorAll('.accept-invitation-action-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const invitationId = this.dataset.invitationId;
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
                                    ? 'Anda akan masuk ke tahap <strong>wawancara</strong>' 
                                    : 'Anda akan langsung <strong>diterima</strong> untuk posisi ini'}
                            </div>
                        </div>
                    `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-check-circle me-1"></i>Ya, Terima',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    });

                    if (!result.isConfirmed) return;

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

            // ===== REJECT INVITATION =====
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
                    document.getElementById('reject-invitation-company-name').textContent =
                        companyName;
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

                    if (reason.length < 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: 'Alasan harus minimal 10 karakter',
                            confirmButtonColor: '#ffc107'
                        });
                        return;
                    }

                    const modalElement = document.getElementById('rejectInvitationModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();

                    // TODO: Implement reject invitation API
                    console.log('📤 Rejecting invitation:', {
                        invitationId,
                        reason
                    });

                    Swal.fire({
                        icon: 'info',
                        title: 'Coming Soon',
                        text: 'Fitur tolak undangan sedang dalam pengembangan'
                    });
                });
            }

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
                        'invited': 'warning',
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
                        console.log('📥 Withdraw response:', data);

                        if (response.ok && data.success) {
                            let html = `<p>${data.message}</p>`;

                            if (data.data.penalty_applied > 0) {
                                html += `
                                <div class="alert alert-warning mt-3">
                                    <strong>Poin Anda:</strong> ${data.data.old_point} → ${data.data.new_point}
                                    <br><small>Penalty: -${data.data.penalty_applied} poin</small>
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

            // ===== REPORT COMPANY =====
            document.querySelectorAll('.report-company-btn').forEach(button => {
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
                    document.getElementById('report-reason-select').value = '';

                    const counter = document.getElementById('char-count');
                    if (counter) counter.textContent = '0';

                    new bootstrap.Modal(document.getElementById('reportModal')).show();
                });
            });

            const reportReasonSelect = document.getElementById('report-reason-select');
            if (reportReasonSelect) {
                reportReasonSelect.addEventListener('change', function() {
                    const selectedReason = this.value;
                    const reasonTextarea = document.getElementById('report-reason');

                    if (selectedReason && selectedReason !== 'Lainnya') {
                        reasonTextarea.value = selectedReason + ': ';
                        reasonTextarea.dispatchEvent(new Event('input'));
                    } else if (selectedReason === 'Lainnya') {
                        reasonTextarea.value = '';
                        reasonTextarea.dispatchEvent(new Event('input'));
                    }
                });
            }

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
    </script>
@endsection
