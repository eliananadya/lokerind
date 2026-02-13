@extends('layouts.main')

@section('content')
    <style>
        .applied-job {
            position: relative;
            opacity: 0.95;
        }

        .job-card-clickable {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .job-card-clickable:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        #sortBy {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.5rem 2.5rem 0.5rem 0.75rem;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            width: auto;
            max-width: 200px;
            /* ✅ BATAS MAKSIMAL */
        }

        @media (max-width: 768px) {
            #sortBy {
                width: 100%;
                max-width: 100%;
            }
        }

        .applied-job .card-body {
            position: relative;
            z-index: 1;
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

        /* Similarity score badge styling */
        .similarity-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 5;
        }

        .similarity-high {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .similarity-medium {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .similarity-low {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .card:has(.similarity-badge) .card-body {
            padding-top: 3.5rem !important;
        }

        .card.has-similarity .card-body {
            padding-top: 3.5rem !important;
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
            background-color: #ffffff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .select2-dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            background-color: #ffffff !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-blue) !important;
            color: #ffffff !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }
    </style>

    {{-- Search Section --}}
    <section class="py-5" style="background-color: var(--bg-blue);">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search Job Title"
                            name="title" id="title">
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <select class="form-select select2-dropdown" name="city" id="city"
                        data-placeholder="Pilih Kota">
                        <option value=""></option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <select class="form-select select2-dropdown" name="type_job" id="type_job"
                        data-placeholder="Pilih Tipe Pekerjaan">
                        <option value=""></option>
                        @foreach ($typeJobs as $typeJob)
                            <option value="{{ $typeJob->id }}">{{ $typeJob->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <select class="form-select select2-dropdown" name="industry" id="industry"
                        data-placeholder="Pilih Industri">
                        <option value=""></option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <select class="form-select select2-dropdown" name="days[]" id="days" multiple
                        data-placeholder="Pilih Hari">
                        @foreach ($days as $day)
                            <option value="{{ $day->id }}">{{ $day->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary-custom flex-grow-1 text-white" id="filterBtn">
                            <i class="bi bi-funnel me-2"></i>Cari Lowongan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Job Listings Section --}}
    <section class="py-5">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center mb-4 gap-3">
                <h2 class="fw-bold mb-0">Lowongan yang Direkomendasikan</h2>
                <div class="ms-lg-auto">
                    <select class="form-select" id="sortBy">
                        <option value="name_asc">Sort by: Nama A-Z</option>
                        <option value="name_desc">Sort by: Nama Z-A</option>
                        <option value="date_desc">Sort by: Upload Terbaru</option>
                        <option value="date_asc">Sort by: Upload Terlama</option>
                        <option value="salary_desc">Sort by: Gaji Tertinggi</option>
                        <option value="salary_asc">Sort by: Gaji Terendah</option>
                    </select>
                </div>
            </div>

            <div class="row g-4" id="job-listings">
                @foreach ($jobPostings as $job)
                    <div class="col-lg-6">
                        <div class="card h-100 {{ in_array($job->id, $appliedJobIds) ? 'applied-job' : '' }} border-2 shadow-sm job-card-clickable {{ isset($job->similarity_score) ? 'has-similarity' : '' }}"
                            style="border-color: var(--primary-blue) !important; border-radius: 12px; position: relative;"
                            data-job-id="{{ $job->id }}"
                            data-has-applied="{{ in_array($job->id, $appliedJobIds) ? 'true' : 'false' }}">

                            @if (in_array($job->id, $appliedJobIds))
                                <div class="position-absolute end-0 top-0 m-3" style="z-index: 10;">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Sudah Melamar
                                    </span>
                                </div>
                            @endif

                            @if (isset($job->similarity_score))
                                @php
                                    $score = $job->similarity_score;
                                    $badgeClass =
                                        $score >= 70
                                            ? 'similarity-high'
                                            : ($score >= 40
                                                ? 'similarity-medium'
                                                : 'similarity-low');
                                @endphp
                                <div class="similarity-badge">
                                    <span class="badge {{ $badgeClass }} text-white">
                                        <i class="bi bi-star-fill me-1"></i>{{ number_format($score, 0) }}% Match
                                    </span>
                                </div>
                            @endif

                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-2">{{ $job->title }}</h5>
                                        <p class="text-muted mb-0">{{ $job->company->name }}</p>
                                    </div>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($job->updated_at)->format('d M Y') }}</small>
                                </div>

                                <div class="mb-3">
                                    <span class="badge bg-light text-dark me-2">{{ $job->typeJobs->name ?? 'N/A' }}</span>
                                    <span class="badge bg-light text-dark">{{ $job->industry->name ?? 'N/A' }}</span>
                                </div>

                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt me-2"></i>{{ $job->city->name }}
                                </p>

                                @if (isset($applicationMessages[$job->id]) && !empty($applicationMessages[$job->id]))
                                    <div class="alert alert-info mb-3 py-2" role="alert">
                                        <i class="bi bi-envelope-fill me-2"></i>
                                        <strong>Pesan dari Perusahaan:</strong>
                                        <p class="mb-0 mt-1 small">{{ $applicationMessages[$job->id] }}</p>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
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
                                                        $closeDate = \Carbon\Carbon::parse($job->close_recruitment);
                                                        $now = \Carbon\Carbon::now();
                                                        $daysLeft = $now->diffInDays($closeDate, false);
                                                    @endphp
                                                    <span
                                                        class="{{ $daysLeft <= 3 && $daysLeft >= 0 ? 'text-danger fw-bold' : '' }}">
                                                        {{ $closeDate->format('d M Y') }}
                                                        @if ($daysLeft > 0)
                                                            ({{ $daysLeft }} hari lagi)
                                                        @elseif ($daysLeft == 0)
                                                            <span class="badge bg-warning text-dark">Hari Terakhir!</span>
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
                                                        <span class="badge bg-secondary" style="font-size: 0.65rem;">
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

                                    <div class="d-flex align-items-center mt-4 gap-3">
                                        <small class="text-muted">Slot : {{ $job->slot }}</small>
                                        <button type="button" class="btn-add-bookmark"
                                            data-job-id="{{ $job->id }}">
                                            <i class="bi bi-bookmark{{ in_array($job->id, $savedJobIds) ? '-fill' : '' }}"
                                                style="font-size: 1.5rem; {{ in_array($job->id, $savedJobIds) ? 'color: #ffc107;' : 'color: #6c757d;' }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4" id="pagination-container">
                {{ $jobPostings->links() }}
            </div>
        </div>
    </section>

    {{-- Job Detail Modal --}}
    <div class="modal fade" id="jobDetailModal" tabindex="-1" aria-labelledby="jobDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                                        <button class="btn btn-primary w-100" id="apply-btn" style="max-width: 200px;">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
                let currentPage = 1;
                let totalPages = 1;
                let appliedJobIds = @json($appliedJobIds);
                let savedJobIds = @json($savedJobIds);

                $('.select2-dropdown').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: function() {
                        return $(this).data('placeholder');
                    }
                });

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

                // ========== BOOKMARK HANDLER (FIX) ==========
                $(document).on('click', '.btn-add-bookmark', function(e) {
                    e.stopPropagation();
                    e.preventDefault();

                    const $button = $(this);
                    const jobId = parseInt($button.data('job-id'));
                    const $icon = $button.find('i');
                    const isSaved = savedJobIds.includes(jobId);

                    console.log('🔖 Bookmark clicked, Job ID:', jobId, 'isSaved:', isSaved);

                    if (isSaved) {
                        unsaveJob(jobId, $icon);
                    } else {
                        saveJob(jobId, $icon);
                    }
                });

                // ✅ FIX SAVE JOB FUNCTION
                // ✅ SAVE JOB FUNCTION DENGAN AUTH CHECK
                function saveJob(jobId, $icon) {
                    // 🔐 CEK APAKAH USER SUDAH LOGIN
                    @guest
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Login',
                        text: 'Anda harus login terlebih dahulu untuk menyimpan pekerjaan',
                        showCancelButton: true,
                        confirmButtonText: 'Login Sekarang',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('login') }}';
                        }
                    });
                    return; // ❌ STOP EXECUTION
                @endguest

                // ✅ JIKA SUDAH LOGIN, LANJUTKAN SAVE
                $.ajax({
                    url: '{{ route('save.job-history') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        job_posting_id: jobId
                    },
                    success: function(response) {
                        if (response.success) {
                            $icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill').css('color',
                                '#ffc107');
                            if (!savedJobIds.includes(jobId)) savedJobIds.push(jobId);
                            updateModalSaveButton(jobId, true);
                            showToast('success', 'Berhasil!', 'Pekerjaan disimpan');
                        }
                    },
                    error: function(xhr) {
                        console.error('❌ Save error:', xhr);
                        showToast('error', 'Gagal', xhr.responseJSON?.message || 'Gagal menyimpan');
                    }
                });
            }

            // ✅ FIX UNSAVE JOB FUNCTION - TAMBAHKAN job_posting_id
            function unsaveJob(jobId, $icon) {
                console.log('🗑️ Unsaving job, ID:', jobId);

                $.ajax({
                    url: '{{ route('unsave.job-history') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        job_posting_id: jobId // ✅ PERBAIKAN: Gunakan job_posting_id
                    },
                    success: function(response) {
                        console.log('✅ Unsave success:', response);
                        if (response.success) {
                            $icon.removeClass('bi-bookmark-fill').addClass('bi-bookmark').css('color',
                                '#6c757d');
                            const index = savedJobIds.indexOf(jobId);
                            if (index > -1) savedJobIds.splice(index, 1);
                            updateModalSaveButton(jobId, false);
                            showToast('success', 'Berhasil!', 'Pekerjaan dihapus dari favorit');
                        }
                    },
                    error: function(xhr) {
                        console.error('❌ Unsave error:', xhr);
                        showToast('error', 'Gagal', xhr.responseJSON?.message || 'Gagal menghapus');
                    }
                });
            }

            // ========== UPDATE MODAL SAVE BUTTON ==========
            function updateModalSaveButton(jobId, isSaved) {
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
            }

            // ========== SORT HANDLER ==========
            $('#sortBy').on('change', function() {
                const sortValue = $(this).val();
                const url = new URL(window.location.href);
                url.searchParams.set('sort_by', sortValue);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });

            const urlParams = new URLSearchParams(window.location.search);
            const sortParam = urlParams.get('sort_by');
            if (sortParam) $('#sortBy').val(sortParam);

            // ========== CARD CLICK TO OPEN MODAL ==========
            $(document).on('click', '.job-card-clickable', function(e) {
                if ($(e.target).closest('.btn-add-bookmark').length) return;

                const jobId = $(this).data('job-id');
                const hasApplied = $(this).data('has-applied') === 'true';
                loadJobModal(jobId, hasApplied);
            });

            // ========== LOAD JOB MODAL (FIX - TAMBAH JADWAL & CLOSE DATE) ==========
            function loadJobModal(jobId, hasApplied) {
                $.ajax({
                    url: '/jobs/' + jobId,
                    method: 'GET',
                    success: function(data) {
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

                        // ✅ SALARY + CLOSE DATE + JADWAL KERJA
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
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-people-fill me-1"></i>Slot: ${job.slot}
                                </small>
                        `;

                        // ✅ CLOSE RECRUITMENT
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

                        // ✅ JADWAL KERJA
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

                        // Informasi Lowongan Tab
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

                        // Kualifikasi Tab
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

                        // Benefit Tab
                        $('#benefit-content').html(job.benefits && job.benefits.length > 0 ?
                            job.benefits.map(b => `
                                <div class="card shadow-sm mt-2">
                                    <div class="card-body">
                                        <h6 class="fw-bold">${b.benefit?.name || 'Benefit'}</h6>
                                        <p class="mb-0">Jumlah: ${b.amount || 'In Kind'}</p>
                                    </div>
                                </div>
                            `).join('') :
                            '<p class="text-center text-muted">Tidak ada benefit</p>'
                        );

                        // Apply Button State
                        const $applyBtn = $('#apply-btn');
                        if (data.hasApplied || hasApplied) {
                            $applyBtn.prop('disabled', true).removeClass('btn-primary').addClass(
                                'btn-success');
                            $applyBtn.find('.apply-text').text('Sudah Melamar');
                        } else {
                            $applyBtn.prop('disabled', false).removeClass('btn-success').addClass(
                                'btn-primary');
                            $applyBtn.find('.apply-text').text('Apply');
                        }

                        // Save Button State
                        const $saveBtn = $('#save-btn-modal');
                        const isSaved = savedJobIds.includes(parseInt(jobId));

                        if (isSaved) {
                            $saveBtn.removeClass('btn-outline-warning').addClass('btn-warning');
                            $saveBtn.find('i').removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                            $saveBtn.find('.save-text').text('Sudah Disimpan');
                        } else {
                            $saveBtn.removeClass('btn-warning').addClass('btn-outline-warning');
                            $saveBtn.find('i').removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                            $saveBtn.find('.save-text').text('Simpan');
                        }

                        $('#jobDetailModal').modal('show');
                    },
                    error: function() {
                        showToast('error', 'Error', 'Gagal memuat detail pekerjaan');
                    }
                });
            }

            // ========== SAVE/UNSAVE FROM MODAL (FIX) ==========
            $(document).on('click', '#save-btn-modal', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const jobId = parseInt($('#job-info-card').data('job-id'));
                const $icon = $(this).find('i');
                const isSaved = savedJobIds.includes(jobId);

                console.log('💾 Modal save button clicked, Job ID:', jobId, 'isSaved:', isSaved);

                if (isSaved) {
                    unsaveJob(jobId, $icon);
                } else {
                    saveJob(jobId, $icon);
                }
            });

            // ========== APPLY JOB ==========
            $('#apply-btn').on('click', function() {
                    const jobId = $('#job-info-card').data('job-id');

                    @guest
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Login',
                        text: 'Login terlebih dahulu',
                        showCancelButton: true,
                        confirmButtonText: 'Login'
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = '{{ route('login') }}';
                    });
                    return;
                @endguest

                $.ajax({
                    url: '{{ route('apply.job') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        job_posting_id: jobId
                    },
                    success: function(response) {
                        showToast('success', 'Berhasil!', 'Lamaran terkirim');
                        $('#apply-btn').prop('disabled', true).removeClass('btn-primary').addClass(
                            'btn-success');
                        $('#apply-btn .apply-text').text('Sudah Melamar');
                    },
                    error: function(xhr) {
                        showToast('error', 'Gagal', xhr.responseJSON?.message || 'Gagal melamar');
                    }
                });
            });

        // ========== FILTER ==========
        $('#filterBtn').on('click', function() {
            currentPage = 1;
            performSearch();
        });


        function performSearch() {
            const params = {
                title: $('#title').val(),
                city: $('#city').val(),
                type_job: $('#type_job').val(),
                industry: $('#industry').val(),
                days: $('#days').val(),
                sort_by: $('#sortBy').val(),
                page: currentPage
            };

            $('#job-listings').hide();

            $.ajax({
                url: '{{ route('search.jobs') }}',
                method: 'GET',
                data: params,
                success: function(data) {
                    $('#job-listings').show();

                    if (data.appliedJobIds) appliedJobIds = data.appliedJobIds;
                    if (data.savedJobIds) savedJobIds = data.savedJobIds;

                    const $listings = $('#job-listings');
                    $listings.empty();

                    if (data.jobs && data.jobs.length > 0) {
                        data.jobs.forEach(job => {
                            const hasApplied = appliedJobIds.includes(job.id);
                            const isSaved = savedJobIds.includes(job.id);
                            $listings.append(createJobCard(job, hasApplied, isSaved));
                        });

                        generatePagination(data.total, data.per_page, data.current_page);
                    } else {
                        $listings.html(`
                                <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                        <h5 class="text-muted mt-3">Tidak Ada Hasil</h5>
                        <p class="text-muted">Coba ubah filter pencarian Anda</p>
                    </div>
                            `);
                    }
                },
                error: function() {
                    $('#job-listings').show();
                    showToast('error', 'Error', 'Gagal melakukan pencarian');
                }
            });
        }

        // ✅ CREATE JOB CARD LENGKAP DENGAN JADWAL & CLOSE DATE
        function createJobCard(job, hasApplied, isSaved) {
            const appliedBadge = hasApplied ? `
                    <div class="position-absolute end-0 top-0 m-3" style="z-index: 10;">
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Sudah Melamar
                        </span>
                    </div>
                ` : '';

            const similarityBadge = job.similarity_score ? `
                    <div class="similarity-badge">
                        <span class="badge ${job.similarity_score >= 70 ? 'similarity-high' : job.similarity_score >= 40 ? 'similarity-medium' : 'similarity-low'} text-white">
                            <i class="bi bi-star-fill me-1"></i>${Math.round(job.similarity_score)}% Match
                        </span>
                    </div>
                ` : '';

            // ✅ CLOSE RECRUITMENT
            let closeHTML = '';
            if (job.close_recruitment) {
                const closeDate = new Date(job.close_recruitment);
                const now = new Date();
                const daysLeft = Math.ceil((closeDate - now) / (1000 * 60 * 60 * 24));

                closeHTML = `
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar-x me-1"></i>
                                <strong>Tutup:</strong>
                                <span class="${daysLeft <= 3 && daysLeft >= 0 ? 'text-danger fw-bold' : ''}">
                                    ${formatDate(job.close_recruitment)}
                                    ${daysLeft > 0 ? `(${daysLeft} hari lagi)` : daysLeft === 0 ? '<span class="badge bg-warning text-dark">Hari Terakhir!</span>' : '<span class="badge bg-danger">Sudah Ditutup</span>'}
                                </span>
                            </small>
                        </div>
                    `;
            }

            // ✅ JADWAL KERJA
            let scheduleHTML = '';
            if (job.jobDatess && job.jobDatess.length > 0) {
                scheduleHTML = `
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                <strong>Jadwal Kerja:</strong>
                            </small>
                    `;

                const maxDisplay = Math.min(3, job.jobDatess.length);
                for (let i = 0; i < maxDisplay; i++) {
                    const jobDate = job.jobDatess[i];
                    scheduleHTML += `
                            <small class="text-muted d-block ms-3">
                                <i class="bi bi-dot"></i>
                                ${formatDate(jobDate.date)}
                                ${jobDate.day ? `<span class="badge bg-info text-white ms-1" style="font-size: 0.65rem;">${jobDate.day.name}</span>` : ''}
                                ${jobDate.start_time && jobDate.end_time ? `<span class="ms-1"><i class="bi bi-clock me-1"></i>${jobDate.start_time.substring(0,5)} - ${jobDate.end_time.substring(0,5)}</span>` : ''}
                            </small>
                        `;
                }

                if (job.jobDatess.length > 3) {
                    scheduleHTML += `
                            <small class="text-muted d-block ms-3 mt-1">
                                <span class="badge bg-secondary" style="font-size: 0.65rem;">
                                    +${job.jobDatess.length - 3} jadwal lainnya
                                </span>
                            </small>
                        `;
                }

                scheduleHTML += '</div>';
            }

            return `
                    <div class="col-lg-6">
                        <div class="card h-100 ${hasApplied ? 'applied-job' : ''} border-2 shadow-sm job-card-clickable" 
                            style="border-color: var(--primary-blue) !important; border-radius: 12px; position: relative;" 
                            data-job-id="${job.id}" 
                            data-has-applied="${hasApplied}">
                            
                            ${appliedBadge}
                            ${similarityBadge}
                            
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-2">${job.title}</h5>
                                        <p class="text-muted mb-0">${job.company?.name || 'N/A'}</p>
                                    </div>
                                    <small class="text-muted">${formatDate(job.updated_at)}</small>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark me-2">${job.type_jobs?.name || 'N/A'}</span>
                                    <span class="badge bg-light text-dark">${job.industry?.name || 'N/A'}</span>
                                </div>
                                
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt me-2"></i>${job.city?.name || 'N/A'}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">
                                            Rp ${formatNumber(job.salary)}
                                        </h4>
                                        <div class="mb-2">
                                            <span class="badge bg-primary" style="font-size: 0.75rem;">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                ${job.type_salary === 'total' ? 'Total' : 'Per Hari'}
                                            </span>
                                        </div>
                                        
                                        ${closeHTML}
                                        ${scheduleHTML}
                                    </div>
                                    
                                    <div class="d-flex flex-column align-items-end gap-2 ms-3">
                                        <small class="text-muted">Slot: ${job.slot}</small>
                                        <button type="button" class="btn-add-bookmark" data-job-id="${job.id}">
                                            <i class="bi ${isSaved ? 'bi-bookmark-fill' : 'bi-bookmark'}" 
                                                style="font-size: 1.5rem; color: ${isSaved ? '#ffc107' : '#6c757d'}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        }

        function generatePagination(total, perPage, current) {
            totalPages = Math.ceil(total / perPage);
            const $container = $('#pagination-container');

            if (totalPages <= 1) {
                $container.hide();
                return;
            }

            let html = '<nav><ul class="pagination justify-content-center">';

            html += current > 1 ?
                `<li class="page-item"><a class="page-link pagination-link" href="#" data-page="${current - 1}">&laquo;</a></li>` :
                `<li class="page-item disabled"><span class="page-link">&laquo;</span></li>`;

            for (let i = 1; i <= totalPages; i++) {
                if (i === current) {
                    html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else {
                    html +=
                        `<li class="page-item"><a class="page-link pagination-link" href="#" data-page="${i}">${i}</a></li>`;
                }
            }

            html += current < totalPages ?
                `<li class="page-item"><a class="page-link pagination-link" href="#" data-page="${current + 1}">&raquo;</a></li>` :
                `<li class="page-item disabled"><span class="page-link">&raquo;</span></li>`;

            html += '</ul></nav>';
            $container.html(html).show();
        }

        $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('page'));
        performSearch();
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
        });
        });
    </script>
@endsection
