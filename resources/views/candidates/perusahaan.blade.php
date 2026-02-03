@extends('layouts.main')
<style>
    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #0d6efd, #0a58ca) !important;
    }

    .badge.bg-info {
        background: linear-gradient(135deg, #0dcaf0, #0aa2c0) !important;
    }

    .bookmark-job-btn {
        transition: all 0.2s ease;
    }

    .bookmark-job-btn:hover {
        transform: scale(1.15);
    }

    .bookmark-job-btn i {
        transition: all 0.2s ease;
    }

    .bookmark-job-btn:hover i {
        color: #ffc107 !important;
    }

    .card-body h5 {
        color: var(--primary-blue);
    }

    .card-body .text-muted {
        font-size: 0.9rem;
    }

    .card-body small {
        font-size: 0.85rem;
    }

    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .job-card-clickable {
        cursor: pointer;
    }

    .company-card {
        cursor: pointer;
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease;
    }

    .company-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .badge {
        font-weight: 500;
    }

    .pagination-link {
        cursor: pointer;
    }

    .pagination-link:hover {
        background-color: #f8f9fa;
    }

    #company-listings,
    #pagination-container {
        transition: opacity 0.3s ease;
    }

    .jobs-pagination-link {
        cursor: pointer;
    }

    .jobs-pagination-link:hover {
        background-color: #f8f9fa;
    }

    #sortBy {
        min-width: 200px;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem 2.5rem 0.5rem 0.75rem;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    #sortBy:hover {
        border-color: #86b7fe;
    }

    @media (max-width: 768px) {
        #sortBy {
            width: 100%;
            min-width: unset;
        }
    }

    #jobs-pagination-container .pagination {
        margin-top: 1rem;
        margin-bottom: 0;
    }

    #company-jobs {
        transition: opacity 0.3s ease;
    }

    .bookmark-job-btn {
        transition: all 0.2s ease;
    }

    .bookmark-job-btn:hover {
        transform: scale(1.1);
    }

    .bookmark-job-btn i {
        transition: all 0.2s ease;
    }

    .select2-container--default .select2-selection--single,
    .select2-dropdown,
    .select2-search--dropdown,
    .select2-search--dropdown .select2-search__field,
    .select2-results,
    .select2-results__options,
    .select2-results__option {
        background-color: #ffffff !important;
    }

    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding: 0.375rem 0.75rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5 !important;
        padding-left: 0 !important;
        color: #212529 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d !important;
        font-style: italic;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }

    .select2-dropdown {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .select2-search--dropdown {
        padding: 0.5rem !important;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding: 0.375rem 0.75rem !important;
    }

    .select2-results__option {
        padding: 0.5rem 0.75rem !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0d6efd !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #e9ecef !important;
        color: #212529 !important;
    }

    .select2-container--default .select2-results__option--selectable:hover {
        background-color: #f8f9fa !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #86b7fe !important;
        outline: 0 !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        font-size: 1.2rem;
        margin-right: 5px;
        color: #6c757d !important;
    }

    @media (max-width: 768px) {
        .select2-container--default .select2-selection--single {
            font-size: 0.9rem;
        }
    }
</style>
@section('content')
    <section class="py-5" style="background-color: var(--bg-blue);">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Cari Nama Perusahaan"
                            name="name" id="name">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <select class="form-select select2-dropdown" name="industry" id="industry"
                        data-placeholder="Pilih Industri">
                        <option value=""></option>
                        <option value="">Semua Industri</option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-5 col-md-12">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary-custom flex-grow-1 text-white" id="filterBtn">
                            <i class="bi bi-funnel me-2"></i>Cari Perusahaan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Daftar Perusahaan</h2>
                <select class="form-select" id="sortBy" style="width: auto;">
                    <option value="name_asc">Nama: A-Z</option>
                    <option value="name_desc">Nama: Z-A</option>
                    <option value="rating_desc">Rating: Tertinggi</option>
                    <option value="rating_asc">Rating: Terendah</option>
                    <option value="jobs_desc">Job: Terbanyak</option>
                    <option value="jobs_asc">Job: Tersedikit</option>
                </select>
            </div>

            <div class="row g-4" id="company-listings">
                @foreach ($companies as $company)
                    @php
                        $openJobsCount = $company->jobPostings->where('status', 'Open')->count();
                        $isSubscribed = in_array($company->id, $subscribedCompanyIds ?? []);
                    @endphp
                    <div class="col-lg-6">
                        <div class="card company-card rounded-3 border {{ $isSubscribed ?: '' }}"
                            style="cursor: pointer; position: relative;" data-company-id="{{ $company->id }}"
                            data-bs-toggle="modal" data-bs-target="#companyDetailModal">

                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-2">{{ $company->name }}</h5>
                                        <p class="text-muted mb-2">
                                            {{ $company->industries ? $company->industries->name : 'Tipe Industri' }}
                                        </p>
                                        <p class="text-muted mb-0">
                                            <i
                                                class="bi bi-geo-alt me-1"></i>{{ $company->location ?? 'Lokasi Perusahaan' }}
                                        </p>
                                    </div>
                                    <div class="ms-3">
                                        <div class="d-flex align-items-center gap-2 justify-content-end text-end">

                                            @if ($isSubscribed)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-bell-fill me-1"></i>Subscribed
                                                </span>
                                            @endif

                                            <span class="badge bg-secondary px-3 py-2">
                                                {{ $openJobsCount }} Jobs
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="fw-bold">{{ number_format($company->avg_rating ?? 0, 1) }}</span>
                                        <span class="text-muted ms-2">1 Ulasan</span>
                                    </div>
                                    <a href="#" class="text-decoration-underline"
                                        onclick="event.stopPropagation();">See detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="d-flex justify-content-center mt-4" id="pagination-container">
                {{ $companies->links() }}
            </div>
        </div>
    </section>

    <div class="modal fade" id="companyDetailModal" tabindex="-1" aria-labelledby="companyDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h3 class="fw-bold mb-2" id="modal-company-name">Nama perusahaan</h3>
                            <p class="text-muted mb-2" id="modal-company-industry">Tipe Industri</p>
                            <p class="text-muted mb-2" id="modal-company-location">
                                <i class="bi bi-geo-alt me-1"></i>Lokasi Perusahaan
                            </p>
                            <p class="text-muted mb-0" id="modal-company-joindate">Join date</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span class="fw-bold fs-5" id="modal-company-rating">4.0</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted" id="modal-company-reviews">1 Ulasan</span>
                            </div>
                            <div class="mb-3">
                                <span class="badge bg-secondary px-4 py-2" id="modal-company-jobs">20 Jobs</span>
                            </div>
                            <button class="btn btn-outline-secondary px-4" id="subscribe-btn">
                                Subscribe
                            </button>
                        </div>
                    </div>

                    <ul class="nav nav-tabs border-bottom" id="companyTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="informasi-tab" data-bs-toggle="tab"
                                data-bs-target="#informasi" type="button" role="tab" aria-controls="informasi"
                                aria-selected="true">
                                Informasi Perusahaan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="lowongan-tab" data-bs-toggle="tab" data-bs-target="#lowongan"
                                type="button" role="tab" aria-controls="lowongan" aria-selected="false">
                                Lowongan Pekerjaan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rating-tab" data-bs-toggle="tab" data-bs-target="#rating"
                                type="button" role="tab" aria-controls="rating" aria-selected="false">
                                Rating dan Ulasan
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="companyTabContent">
                        <div class="tab-pane fade show active" id="informasi" role="tabpanel"
                            aria-labelledby="informasi-tab">
                            <div class="bg-light rounded p-4">
                                <h5 class="fw-bold mb-3">Deskripsi</h5>
                                <p class="text-muted" id="company-description">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit...
                                </p>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="lowongan" role="tabpanel" aria-labelledby="lowongan-tab">
                            <div id="company-jobs" class="row g-3">
                            </div>
                            <div class="d-flex justify-content-center mt-4" id="jobs-pagination-container">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="rating" role="tabpanel" aria-labelledby="rating-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-4">Rating Breakdown</h5>
                                    <div id="rating-breakdown">
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="bg-light mb-4 rounded p-4">
                                        <h1 class="display-3 fw-bold mb-0" id="avg-rating-display">4.0</h1>
                                        <div class="mb-2" id="star-rating-display">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star text-warning"></i>
                                        </div>
                                        <p class="text-muted" id="total-ratings">20 ratings</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex justify-content-end mb-3">
                                    <select class="form-select w-auto">
                                        <option>Sort By : Date Recent</option>
                                        <option>Sort By : Highest Rating</option>
                                        <option>Sort By : Lowest Rating</option>
                                    </select>
                                </div>
                                <div id="reviews-list">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                <p class="text-muted mb-2" id="job-location"><i class="bi bi-geo-alt me-2"></i></p>
                                <div class="d-flex justify-content-between align-items-center" id="salary-slot"></div>
                                <div class="text-end mt-3">
                                    <div class="d-flex flex-column align-items-end gap-2">
                                        <button class="btn btn-primary w-100" id="apply-btn" style="max-width: 200px;">
                                            <span class="apply-text">
                                                <i class="bi bi-send me-1"></i>Apply
                                            </span>

                                        </button>

                                        <button class="btn btn-outline-warning w-100" id="save-btn-modal"
                                            style="max-width: 200px;" data-job-id="">
                                            <i class="bi bi-bookmark me-1"></i>
                                            <span class="save-text">Simpan</span>
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
                console.log('✅ Company listing page initialized');
                $('.select2-dropdown').select2({
                    placeholder: 'Pilih Industri',
                    allowClear: true,
                    width: '100%'
                });
                var currentCompanyData = null;
                var currentCompanyId = null;
                var isSubscribed = false;
                var currentJobsPage = 1;
                var jobsPerPage = 10;
                var allOpenJobs = [];
                var subscribedCompanyIdsGlobal = @json($subscribedCompanyIds);
                var savedJobIdsGlobal = @json($savedJobIds ?? []);

                console.log('Initial Subscribed Companies:', subscribedCompanyIdsGlobal);
                console.log('Initial Saved Jobs:', savedJobIdsGlobal);

                $('#industry').select2({
                    placeholder: 'Pilih Industri',
                    allowClear: true,
                    width: '100%'
                });

                $('#industry').on('select2:open', function() {
                    $('.select2-search__field').attr('placeholder', 'Ketik untuk mencari industri...');
                });

                $('#industry').on('change', function() {
                    console.log('Industry changed:', $(this).val());
                });

                $('#companyDetailModal').on('hidden.bs.modal', function() {
                    currentCompanyData = null;
                    currentCompanyId = null;
                    isSubscribed = false;
                    currentJobsPage = 1;
                    allOpenJobs = [];

                    $('#modal-company-name').text('Nama perusahaan');
                    $('#modal-company-industry').text('Tipe Industri');
                    $('#modal-company-location').html('<i class="bi bi-geo-alt me-1"></i>Lokasi Perusahaan');
                    $('#modal-company-joindate').text('Join date');
                    $('#modal-company-jobs').text('0 Jobs');
                    $('#company-description').text('');
                    $('#company-jobs').html('<div class="col-12"><p class="text-center">Loading...</p></div>');
                    $('#reviews-list').html('<div class="text-center">Loading...</div>');
                    $('#jobs-pagination-container').empty();

                    updateSubscribeButton(false);
                    $('#informasi-tab').tab('show');
                });

                function checkSubscriptionStatus(companyId) {
                    @auth
                    if (subscribedCompanyIdsGlobal.includes(companyId)) {
                        isSubscribed = true;
                        updateSubscribeButton(true);
                        console.log('Subscription Status (from global):', isSubscribed);
                    } else {
                        $.ajax({
                            url: '/companies/' + companyId,
                            method: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                isSubscribed = data.isSubscribed || false;
                                updateSubscribeButton(isSubscribed);
                                console.log('Subscription Status (from server):', isSubscribed);
                            },
                            error: function(error) {
                                console.log('Error checking subscription:', error);
                                isSubscribed = false;
                                updateSubscribeButton(false);
                            }
                        });
                    }
                @else
                    isSubscribed = false;
                    updateSubscribeButton(false);
                @endauth
            }

            $(document).on('click', '.company-card', function() {
                var companyId = $(this).data('company-id');
                currentCompanyId = companyId;

                console.log('Company ID clicked:', currentCompanyId);

                $.ajax({
                    url: '/companies/' + companyId,
                    method: 'GET',
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        currentCompanyData = data;

                        console.log('Company Data:', data.company.name);
                        console.log('Job Postings:', data.company.job_postings);

                        isSubscribed = data.isSubscribed || false;
                        updateSubscribeButton(isSubscribed);
                        checkSubscriptionStatus(currentCompanyId);

                        $('#modal-company-name').text(data.company.name || 'Nama perusahaan');
                        $('#modal-company-industry').text(data.company.industries ? data.company
                            .industries.name : 'Tipe Industri');
                        $('#modal-company-location').html('<i class="bi bi-geo-alt me-1"></i>' + (data
                            .company.location || 'Lokasi tidak tersedia'));

                        var joinDate = data.company.created_at ? new Date(data.company.created_at)
                            .toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }) : 'Tanggal tidak tersedia';
                        $('#modal-company-joindate').text('Join date: ' + joinDate);

                        var rating = parseFloat(data.company.avg_rating) || 0;
                        $('#modal-company-rating').text(rating.toFixed(1));
                        $('#avg-rating-display').text(rating.toFixed(1));

                        var totalReviews = data.total_reviews || 0;
                        $('#modal-company-reviews').text(totalReviews + ' Ulasan');
                        $('#total-ratings').text(totalReviews + ' ratings');

                        var stars = '';
                        for (var i = 1; i <= 5; i++) {
                            stars += i <= Math.floor(rating) ?
                                '<i class="bi bi-star-fill text-warning"></i>' :
                                '<i class="bi bi-star text-warning"></i>';
                        }
                        $('#star-rating-display').html(stars);

                        var openJobsCount = data.company.job_postings ? data.company.job_postings
                            .filter(job => job.status === 'Open').length : 0;
                        $('#modal-company-jobs').text(openJobsCount + ' Jobs');

                        $('#company-description').text(data.company.description ||
                            'Deskripsi tidak tersedia');

                        var ratingBreakdownHTML = '';
                        var ratingStats = data.rating_stats || {
                            5: 0,
                            4: 0,
                            3: 0,
                            2: 0,
                            1: 0
                        };
                        var maxCount = Math.max(...Object.values(ratingStats));

                        for (var star = 5; star >= 1; star--) {
                            var count = ratingStats[star] || 0;
                            var percentage = maxCount > 0 ? (count / maxCount * 100) : 0;
                            ratingBreakdownHTML += `
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill me-2"></i>
                                    <span class="me-2">${star}</span>
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: ${percentage}%"></div>
                                    </div>
                                    <span>${count}</span>
                                </div>
                            </div>
                        `;
                        }
                        $('#rating-breakdown').html(ratingBreakdownHTML);

                        var reviewsHTML = '';
                        if (data.company.reviews && data.company.reviews.length > 0) {
                            data.company.reviews.forEach(function(review) {
                                var reviewDate = review.updated_at ? new Date(review.updated_at)
                                    .toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric'
                                    }) : 'DD/MM/YYYY';

                                var starHTML = '';
                                for (var i = 1; i <= 5; i++) {
                                    starHTML += i <= review.rating_company ?
                                        '<i class="bi bi-star-fill text-warning"></i>' :
                                        '<i class="bi bi-star text-warning"></i>';
                                }

                                reviewsHTML += `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <h6 class="fw-bold mb-0">${review.candidate ?  review.candidate.name : 'Anonymous'}</h6>
                                            <small class="text-muted">${reviewDate}</small>
                                        </div>
                                        <div class="mb-2">${starHTML}</div>
                                        <p class="text-muted mb-0">${review.review_company || 'Tidak ada komentar'}</p>
                                    </div>
                                </div>
                            `;
                            });
                        } else {
                            reviewsHTML =
                                '<div class="alert alert-light text-center">Belum ada ulasan untuk perusahaan ini</div>';
                        }
                        $('#reviews-list').html(reviewsHTML);

                        $('#companyDetailModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error loading company:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal mengambil data perusahaan. Silakan coba lagi.',
                        });
                    }
                });
            });

            $('#subscribe-btn').click(function(event) {
                    event.stopPropagation();

                    console.log('Subscribe button clicked, Company ID:', currentCompanyId);

                    @guest
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Login',
                        text: 'Anda harus login terlebih dahulu untuk subscribe perusahaan.',
                        showCancelButton: true,
                        confirmButtonText: 'Login Sekarang',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#14489b',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(window
                                .location.href);
                        }
                    });
                    return;
                @endguest

                if (!currentCompanyId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Company ID tidak ditemukan! Silakan tutup dan buka kembali modal.',
                    });
                    return;
                }

                var companyName = $('#modal-company-name').text();

                if (isSubscribed) {
                    Swal.fire({
                        title: 'Unsubscribe Perusahaan?',
                        text: `Apakah Anda yakin ingin berhenti mengikuti "${companyName}"?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Unsubscribe',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#dc3545',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            performUnsubscribe(currentCompanyId);
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Subscribe Perusahaan?',
                        text: `Apakah Anda ingin mengikuti "${companyName}" untuk mendapatkan notifikasi lowongan terbaru?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Subscribe',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#28a745',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            performSubscribe(currentCompanyId);
                        }
                    });
                }
            });

        function performSubscribe(companyId) {
            var $btn = $('#subscribe-btn');
            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

            $.ajax({
                url: '{{ route('subscribe.company') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_id: companyId
                },
                success: function(response) {
                    if (response.success) {
                        isSubscribed = true;
                        if (!subscribedCompanyIdsGlobal.includes(companyId)) {
                            subscribedCompanyIdsGlobal.push(companyId);
                        }
                        updateSubscribeButton(true);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat subscribe perusahaan.',
                    });
                    updateSubscribeButton(isSubscribed);
                }
            });
        }

        function performUnsubscribe(companyId) {
            var $btn = $('#subscribe-btn');
            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Loading...');

            $.ajax({
                url: '{{ route('unsubscribe.company') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_id: companyId
                },
                success: function(response) {
                    if (response.success) {
                        isSubscribed = false;
                        subscribedCompanyIdsGlobal = subscribedCompanyIdsGlobal.filter(id => id !==
                            companyId);
                        updateSubscribeButton(false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat unsubscribe perusahaan.',
                    });
                    updateSubscribeButton(isSubscribed);
                }
            });
        }

        function updateSubscribeButton(subscribed) {
            var $btn = $('#subscribe-btn');
            if (subscribed) {
                $btn.removeClass('btn-outline-secondary').addClass('btn-danger')
                    .html('<i class="bi bi-x-circle me-2"></i>Unsubscribe')
                    .prop('disabled', false);
            } else {
                $btn.removeClass('btn-danger').addClass('btn-outline-secondary')
                    .html('<i class="bi bi-bell me-2"></i>Subscribe')
                    .prop('disabled', false);
            }
        }

        function isJobSaved(jobId) {
            return savedJobIdsGlobal.includes(jobId);
        }

        function updateBookmarkIcon($btn, isSaved) {
            if (isSaved) {
                $btn.find('i').removeClass('bi-bookmark').addClass('bi-bookmark-fill').css('color',
                    '#ffc107');
            } else {
                $btn.find('i').removeClass('bi-bookmark-fill').addClass('bi-bookmark').css('color', '');
            }
        }

        function performSaveJob(jobId, $btn) {
            Swal.fire({
                title: 'Simpan Pekerjaan?',
                text: 'Apakah Anda ingin menyimpan pekerjaan ini ke daftar tersimpan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
            }).then((result) => {
                if (result.isConfirmed) {
                    $btn.prop('disabled', true);
                    $.ajax({
                        url: '{{ route('save.job-history') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            job_posting_id: jobId
                        },
                        success: function(response) {
                            if (response.success) {
                                if (!savedJobIdsGlobal.includes(jobId)) {
                                    savedJobIdsGlobal.push(jobId);
                                }
                                updateBookmarkIcon($btn, true);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                            $btn.prop('disabled', false);
                        },
                        error: function(xhr) {
                            $btn.prop('disabled', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan saat menyimpan pekerjaan.',
                            });
                        }
                    });
                }
            });
        }

        function performUnsaveJob(jobId, $btn) {
            $btn.prop('disabled', true);
            $.ajax({
                url: '{{ route('unsave.job-history') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_posting_id: jobId
                },
                success: function(response) {
                    if (response.success) {
                        savedJobIdsGlobal = savedJobIdsGlobal.filter(id => id !== jobId);
                        updateBookmarkIcon($btn, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    $btn.prop('disabled', false);
                },
                error: function(xhr) {
                    $btn.prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menghapus pekerjaan tersimpan.',
                    });
                }
            });
        }

        $('#sortBy').on('change', function() {
            console.log('Sort changed:', $(this).val());
            filterCompanies(1);
        });

        $(document).on('click', '.bookmark-job-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            var jobId = $btn.data('job-id');

            console.log('Bookmark clicked for Job ID:', jobId);

            @guest
            Swal.fire({
                icon: 'warning',
                title: 'Belum Login',
                text: 'Anda harus login terlebih dahulu untuk menyimpan pekerjaan.',
                showCancelButton: true,
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#14489b',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(
                        window.location.href);
                }
            });
            return;
        @endguest

        if (isJobSaved(jobId)) {
            Swal.fire({
                title: 'Hapus dari Tersimpan?',
                text: 'Apakah Anda yakin ingin menghapus pekerjaan ini dari daftar tersimpan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) {
                    performUnsaveJob(jobId, $btn);
                }
            });
        } else {
            performSaveJob(jobId, $btn);
        }
        });

        function renderJobsWithPagination(page = 1) {
            if (!currentCompanyData) return;

            var data = currentCompanyData;
            var jobsHTML = '';

            if (data.company.job_postings && data.company.job_postings.length > 0) {
                allOpenJobs = data.company.job_postings.filter(job => job.status === 'Open');

                if (allOpenJobs.length > 0) {
                    var totalJobs = allOpenJobs.length;
                    var totalPages = Math.ceil(totalJobs / jobsPerPage);
                    var startIndex = (page - 1) * jobsPerPage;
                    var endIndex = startIndex + jobsPerPage;
                    var jobsToDisplay = allOpenJobs.slice(startIndex, endIndex);

                    jobsToDisplay.forEach(function(job) {
                        var createdDate = job.created_at ? new Date(job.created_at).toLocaleDateString(
                            'id-ID', {
                                day: '2-digit',
                                month: 'long'
                            }) : '';

                        var isSaved = isJobSaved(job.id);
                        var bookmarkClass = isSaved ? 'bi-bookmark-fill' : 'bi-bookmark';
                        var bookmarkColor = isSaved ? 'color: #ffc107;' : '';

                        var salaryType = job.type_salary === 'total' ? 'Total' : 'Per Hari';
                        var salaryBadge = `<span class="badge bg-primary" style="font-size: 0.7rem;">
        <i class="bi bi-calendar-check me-1"></i>${salaryType}
    </span>`;

                        var closeRecruitmentHTML = '';
                        if (job.close_recruitment) {
                            var closeDate = new Date(job.close_recruitment);
                            var now = new Date();
                            var diffTime = closeDate - now;
                            var daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            var formattedDate = closeDate.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });

                            var statusHTML = '';
                            var textClass = '';

                            if (daysLeft > 3) {
                                statusHTML = `(${daysLeft} hari lagi)`;
                                textClass = '';
                            } else if (daysLeft > 0) {
                                statusHTML =
                                    `<span class="badge bg-warning text-dark ms-1">${daysLeft} hari lagi!</span>`;
                                textClass = 'text-danger fw-bold';
                            } else if (daysLeft === 0) {
                                statusHTML = `<span class="badge bg-warning text-dark ms-1">Hari Terakhir!</span>`;
                                textClass = 'text-danger fw-bold';
                            } else {
                                statusHTML = `<span class="badge bg-danger ms-1">Sudah Ditutup</span>`;
                                textClass = 'text-muted';
                            }

                            closeRecruitmentHTML = `
            <small class="text-muted d-block mb-2">
                <i class="bi bi-calendar-x me-1"></i>
                <strong>Tutup:</strong>
                <span class="${textClass}">
                    ${formattedDate} ${statusHTML}
                </span>
            </small>
        `;
                        } else {
                            closeRecruitmentHTML = `
            <small class="text-muted d-block mb-2">
                <i class="bi bi-calendar-x me-1"></i>
                <strong>Tutup:</strong> Belum ditentukan
            </small>
        `;
                        }

                        var workScheduleHTML = '';
                        if (job.job_datess && job.job_datess.length > 0) {
                            workScheduleHTML = `
            <div class="mb-2">
                <small class="text-muted d-block mb-1">
                    <i class="bi bi-calendar-event me-1"></i>
                    <strong>Jadwal Kerja:</strong>
                </small>
        `;

                            var maxDisplay = Math.min(3, job.job_datess.length);

                            for (var i = 0; i < maxDisplay; i++) {
                                var scheduleItem = job.job_datess[i]; // ✅ PERBAIKAN: ganti nama variable
                                var scheduleDateFormatted = new Date(scheduleItem.date).toLocaleDateString(
                                    'id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    });
                                var dayName = scheduleItem.day ? scheduleItem.day.name : '';
                                var startTime = scheduleItem.start_time ? scheduleItem.start_time.substring(0, 5) :
                                    '';
                                var endTime = scheduleItem.end_time ? scheduleItem.end_time.substring(0, 5) : '';

                                workScheduleHTML += `
                <small class="text-muted d-block ms-3">
                    <i class="bi bi-dot"></i>
                    ${scheduleDateFormatted}
                    ${dayName ? `<span class="badge bg-info text-white ms-1" style="font-size: 0.65rem;">${dayName}</span>` : ''}
                    ${startTime && endTime ? `<span class="ms-1"><i class="bi bi-clock me-1"></i>${startTime} - ${endTime}</span>` : ''}
                </small>
            `;
                            }

                            if (job.job_datess.length > 3) {
                                workScheduleHTML += `
                <small class="text-muted d-block ms-3 mt-1">
                    <span class="badge bg-secondary" style="font-size: 0.65rem;">
                        +${job.job_datess.length - 3} jadwal lainnya
                    </span>
                </small>
            `;
                            }

                            workScheduleHTML += '</div>';
                        } else {
                            workScheduleHTML = `
            <small class="text-muted d-block">
                <i class="bi bi-calendar-event me-1"></i>
                Tanggal belum ditentukan
            </small>
        `;
                        }

                        jobsHTML += `
        <div class="col-md-6">
            <div class="card h-100 border rounded-3 shadow-sm hover-card job-card-clickable"
                style="cursor: pointer;"
                data-job-id="${job.id}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2">${job.title || 'Judul Pekerjaan'}</h6>
                            <p class="text-muted mb-0">${data.company.name}</p>
                        </div>
                        <small class="text-muted text-nowrap ms-2">${createdDate}</small>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-light text-dark border me-1">${job.type_jobs ? job.type_jobs.name : 'Tipe Pekerjaan'}</span>
                        <span class="badge bg-light text-dark border">${job.industry ? job.industry.name : 'Tipe Industri'}</span>
                    </div>

                    <p class="text-muted mb-2">
                        <i class="bi bi-geo-alt me-1"></i>${job.city ? job.city.name : 'Lokasi Pekerjaan'}
                    </p>

                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1" style="color: var(--primary-blue);">
                                Rp ${parseInt(job.salary || 0).toLocaleString('id-ID')}
                            </h5>
                            <div class="mb-2">
                                ${salaryBadge}
                            </div>

                            ${closeRecruitmentHTML}
                            ${workScheduleHTML}
                        </div>

                        <div class="text-end ms-3">
                            <small class="text-muted d-block mb-2">
                                <strong>Slot:</strong> ${job.slot || 0}
                            </small>
                            <button class="btn btn-link p-0 text-muted bookmark-job-btn"
                                data-job-id="${job.id}"
                                onclick="event.stopPropagation();">
                                <i class="bi ${bookmarkClass}" style="font-size: 1.3rem; ${bookmarkColor}"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
                    });

                    $('#company-jobs').html(jobsHTML);

                    if (totalPages > 1) {
                        updateJobsPagination(page, totalPages);
                    } else {
                        $('#jobs-pagination-container').empty();
                    }
                } else {
                    jobsHTML =
                        '<div class="col-12"><p class="text-muted text-center py-4">Tidak ada lowongan yang sedang dibuka</p></div>';
                    $('#company-jobs').html(jobsHTML);
                    $('#jobs-pagination-container').empty();
                }
            } else {
                jobsHTML =
                    '<div class="col-12"><p class="text-muted text-center py-4">Belum ada lowongan tersedia</p></div>';
                $('#company-jobs').html(jobsHTML);
                $('#jobs-pagination-container').empty();
            }

            console.log('Jobs rendered on page:', page);
        }

        $(document).on('click', '.job-card-clickable', function(e) {
            if ($(e.target).closest('.bookmark-job-btn').length > 0) {
                console.log('Bookmark clicked, ignoring card click');
                return false;
            }

            var jobId = $(this).data('job-id');
            console.log('Job card clicked, Job ID:', jobId);

            $('#companyDetailModal').modal('hide');

            setTimeout(function() {
                loadJobDetailModal(jobId);
            }, 300);
        });

        function loadJobDetailModal(jobId) {
            console.log('📄 Loading job modal for ID:', jobId);

            $.ajax({
                url: '/jobs/' + jobId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('✅ Job data loaded:', data);

                    $('#job-title').text(data.job.title);
                    $('#company-name').text(data.job.company.name);

                    var updatedAt = data.job.updated_at ? new Date(data.job.updated_at) : new Date();
                    var now = new Date();
                    var diffDays = Math.ceil(Math.abs(now - updatedAt) / (1000 * 60 * 60 * 24));
                    var timeAgo = diffDays === 0 ? 'Hari ini' : diffDays === 1 ? 'Kemarin' : diffDays +
                        ' hari lalu';
                    $('#updated-at').text('Diperbarui ' + timeAgo);

                    $('#job-type-industry').html(`
                    <span class="badge bg-light text-dark me-2">${data.job.type_jobs?.name || 'Tipe Pekerjaan N/A'}</span>
                    <span class="badge bg-light text-dark">${data.job.industry?.name || 'Industri N/A'}</span>
                `);

                    $('#job-location').html('<i class="bi bi-geo-alt me-2"></i>' + (data.job.city?.name ||
                        'Lokasi N/A'));

                    var salarySlotHTML = `
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">Rp ${parseInt(data.job.salary || 0).toLocaleString('id-ID')}</h4>

                        <div class="mb-2">
                            <span class="badge bg-primary" style="font-size: 0.75rem;">
                                <i class="bi bi-calendar-check me-1"></i>
                                ${data.job.type_salary === 'total' ? 'Total' : 'Per Hari'}
                            </span>
                        </div>

                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-people-fill me-1"></i>
                            Slot Tersedia: <strong>${data.job.slot || 0}</strong>
                        </small>
                `;

                    if (data.job.close_recruitment) {
                        var closeDate = new Date(data.job.close_recruitment);
                        var now = new Date();
                        var diffTime = closeDate - now;
                        var daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        var formattedDate = closeDate.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        var statusHTML = '';
                        var textClass = '';

                        if (daysLeft > 3) {
                            statusHTML = `(${daysLeft} hari lagi)`;
                        } else if (daysLeft > 0) {
                            statusHTML =
                                `<span class="badge bg-warning text-dark ms-1">${daysLeft} hari lagi!</span>`;
                            textClass = 'text-danger fw-bold';
                        } else if (daysLeft === 0) {
                            statusHTML =
                                `<span class="badge bg-warning text-dark ms-1">Hari Terakhir!</span>`;
                            textClass = 'text-danger fw-bold';
                        } else {
                            statusHTML = `<span class="badge bg-danger ms-1">Sudah Ditutup</span>`;
                            textClass = 'text-muted';
                        }

                        salarySlotHTML += `
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar-x me-1"></i>
                            <strong>Tutup:</strong>
                            <span class="${textClass}">
                                ${formattedDate} ${statusHTML}
                            </span>
                        </small>
                    `;
                    } else {
                        salarySlotHTML += `
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-calendar-x me-1"></i>
                            <strong>Tutup:</strong> Belum ditentukan
                        </small>
                    `;
                    }

                    if (data.job.jobDatess && data.job.jobDatess.length > 0) {
                        salarySlotHTML += `
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                <strong>Jadwal Kerja:</strong>
                            </small>
                    `;

                        data.job.jobDatess.forEach(function(jobDate) {
                            var dateFormatted = new Date(jobDate.date).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });
                            var dayName = jobDate.day ? jobDate.day.name : '';
                            var startTime = jobDate.start_time ? jobDate.start_time.substring(0, 5) :
                                '';
                            var endTime = jobDate.end_time ? jobDate.end_time.substring(0, 5) : '';

                            salarySlotHTML += `
                            <small class="text-muted d-block ms-3">
                                <i class="bi bi-dot"></i>
                                ${dateFormatted}
                                ${dayName ? `<span class="badge bg-info text-white ms-1" style="font-size: 0.65rem;">${dayName}</span>` : ''}
                                ${startTime && endTime ? `<span class="ms-1"><i class="bi bi-clock me-1"></i>${startTime} - ${endTime}</span>` : ''}
                            </small>
                        `;
                        });

                        salarySlotHTML += '</div>';
                    } else {
                        salarySlotHTML += `
                        <small class="text-muted d-block">
                            <i class="bi bi-calendar-event me-1"></i>
                            Tanggal belum ditentukan
                        </small>
                    `;
                    }

                    salarySlotHTML += '</div>';
                    $('#salary-slot').html(salarySlotHTML);

                    $('#job-info-card').data('job-id', jobId);

                    let addressHTML = data.job.address ? data.job.address :
                        '<span class="text-muted">Alamat tidak tersedia</span>';
                    let descriptionHTML = data.job.description ? data.job.description :
                        '<span class="text-muted">Deskripsi tidak tersedia</span>';

                    $('#informasi-lowongan-content').html(`
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>Alamat
                            </h5>
                            <p class="card-text">${addressHTML}</p>
                        </div>
                    </div>
                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">
                                <i class="bi bi-file-text-fill text-primary me-2"></i>Deskripsi Pekerjaan
                            </h5>
                            <div class="card-text">${descriptionHTML}</div>
                        </div>
                    </div>
                `);

                    $('#kualifikasi-content').html(`
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="bi bi-clipboard-check-fill text-primary me-2"></i>Persyaratan
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-translate text-success me-2"></i>
                                        <strong>Bahasa English:</strong>
                                        <span class="ms-2">${data.job.level_english || 'Tidak diwajibkan'}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-translate text-danger me-2"></i>
                                        <strong>Bahasa Mandarin:</strong>
                                        <span class="ms-2">${data.job.level_mandarin || 'Tidak diwajibkan'}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-range text-info me-2"></i>
                                        <strong>Usia:</strong>
                                        <span class="ms-2">${data.job.min_age || 'N/A'} - ${data.job.max_age || 'N/A'} tahun</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-rulers text-warning me-2"></i>
                                        <strong>Tinggi Badan:</strong>
                                        <span class="ms-2">Min. ${data.job.min_height || 'N/A'} cm</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-speedometer2 text-secondary me-2"></i>
                                        <strong>Berat Badan:</strong>
                                        <span class="ms-2">Min. ${data.job.min_weight || 'N/A'} kg</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-gender-ambiguous text-primary me-2"></i>
                                        <strong>Gender:</strong>
                                        <span class="ms-2">${data.job.gender || 'Semua'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="bi bi-star-fill text-warning me-2"></i>Skill yang Dibutuhkan
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            ${data.job.skills && data.job.skills.length > 0 
                                ? data.job.skills.map(skill => `
                                                                                                    <span class="badge bg-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                                                                                        <i class="bi bi-check-circle me-1"></i>${skill.name}
                                                                                                    </span>
                                                                                                `).join('') 
                                : '<span class="text-muted">Tidak ada skill khusus yang dibutuhkan</span>'
                            }
                        </div>
                    </div>
                </div>
            `);

                    let benefitHTML = '';
                    if (data.job.benefits && data.job.benefits.length > 0) {
                        benefitHTML = data.job.benefits.map(benefit => `
                        <div class="card shadow-sm mt-2">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-gift-fill text-success" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">${benefit.benefit?.name || 'Benefit'}</h6>
                                        <p class="text-muted mb-1">
                                            <small>
                                                <strong>Jenis:</strong>
                                                ${benefit.benefit_type === 'cash'
                                                    ? '<span class="badge bg-success">Cash</span>'
                                                    : benefit.benefit_type === 'in_kind'
                                                        ? '<span class="badge bg-info">In Kind</span>'
                                                        : '<span class="text-muted">N/A</span>'}
                                            </small>
                                        </p>
                                        <p class="text-primary mb-0">
                                            <strong>Jumlah:</strong> ${benefit.amount || '<span class="text-muted">N/A</span>'}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    } else {
                        benefitHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-gift text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Benefit tidak tersedia untuk posisi ini</p>
                        </div>
                    `;
                    }
                    $('#benefit-content').html(benefitHTML);

                    var applyBtn = $('#apply-btn');
                    if (data.hasApplied) {
                        applyBtn.prop('disabled', true).removeClass('btn-primary').addClass(
                            'btn-success');
                        applyBtn.find('.apply-text').html(
                            '<i class="bi bi-check-circle me-1"></i>Sudah Melamar');
                    } else {
                        applyBtn.prop('disabled', false).removeClass('btn-success').addClass(
                            'btn-primary');
                        applyBtn.find('.apply-text').html('<i class="bi bi-send me-1"></i>Apply');
                    }

                    var saveBtn = $('#save-btn-modal');
                    var isSaved = savedJobIdsGlobal.includes(jobId);
                    saveBtn.data('job-id', jobId);

                    console.log('🔖 Job saved status:', isSaved);

                    if (isSaved) {
                        saveBtn.removeClass('btn-outline-warning').addClass('btn-warning');
                        saveBtn.find('i').removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                        saveBtn.find('.save-text').text('Sudah Disimpan');
                        saveBtn.data('is-saved', true);
                    } else {
                        saveBtn.removeClass('btn-warning').addClass('btn-outline-warning');
                        saveBtn.find('i').removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                        saveBtn.find('.save-text').text('Simpan');
                        saveBtn.data('is-saved', false);
                    }

                    $('#jobDetailModal').modal('show');

                    console.log('✅ Job modal opened successfully');
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error loading job modal:', xhr, status, error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        html: `
                        <p>Terjadi kesalahan saat memuat detail pekerjaan.</p>
                        <small class="text-muted">Error: ${xhr.status} - ${error}</small>
                    `,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Tutup'
                    });
                }
            });
        }

        $(document).on('click', '#apply-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const jobId = $('#job-info-card').data('job-id');
            const jobTitle = $('#job-title').text();

            console.log('Apply button clicked, Job ID:', jobId);

            @guest
            Swal.fire({
                icon: 'warning',
                title: 'Belum Login',
                text: 'Anda harus login terlebih dahulu untuk melamar pekerjaan.',
                showCancelButton: true,
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#14489b',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(
                        window.location.href);
                }
            });
            return;
        @endguest

        if (!jobId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Job ID tidak ditemukan. Silakan refresh halaman.',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        Swal.fire({
            title: 'Lamar Pekerjaan?',
            html: `
                <div class="text-start">
                    <p class="mb-2">Apakah Anda yakin ingin melamar posisi:</p>
                    <div class="alert alert-info py-2 mb-2">
                        <strong>${jobTitle}</strong>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Lamaran Anda akan dikirim ke perusahaan dan kami akan mengirimkan konfirmasi ke email Anda.
                    </small>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-send me-1"></i>Ya, Lamar Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                performApplyJob(jobId, jobTitle);
            }
        });
        });

        function performApplyJob(jobId, jobTitle) {
            console.log('Applying for job ID:', jobId);

            const $btn = $('#apply-btn');
            $btn.prop('disabled', true);
            $btn.find('.apply-text').hide();
            $btn.find('.apply-loading').show();

            Swal.fire({
                title: 'Mengirim Lamaran...',
                html: '<div class="spinner-border text-primary"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ route('apply.job') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_posting_id: jobId
                },
                success: function(response) {
                    console.log('✅ Apply success:', response);

                    if (response.success) {
                        $btn.removeClass('btn-primary').addClass('btn-success');
                        $btn.find('.apply-text').html(
                            '<i class="bi bi-check-circle me-1"></i>Sudah Melamar');
                        $btn.find('.apply-text').show();
                        $btn.find('.apply-loading').hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Melamar! 🎉',
                            html: `
                            <div class="text-start">
                                <p class="mb-2">${response.message}</p>
                                <div class="alert alert-success py-2 mb-0">
                                    <i class="bi bi-envelope-check me-1"></i>
                                    <small>Cek email Anda untuk konfirmasi lamaran</small>
                                </div>
                            </div>
                        `,
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('❌ Apply error:', xhr);

                    $btn.prop('disabled', false);
                    $btn.find('.apply-text').show();
                    $btn.find('.apply-loading').hide();

                    let errorMessage = 'Terjadi kesalahan saat mengirim lamaran.';

                    if (xhr.status === 401) {
                        errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
                    } else if (xhr.status === 422) {
                        errorMessage = xhr.responseJSON?.message ||
                            'Anda sudah melamar pekerjaan ini sebelumnya.';
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Melamar',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        }

        $(document).on('click', '#save-btn-modal', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const jobId = $button.data('job-id');
            const isSaved = $button.data('is-saved') === true || $button.data('is-saved') === 'true';
            const jobTitle = $('#job-title').text();

            console.log('💾 Save button clicked, Job ID:', jobId, 'isSaved:', isSaved);

            @guest
            Swal.fire({
                icon: 'warning',
                title: 'Belum Login',
                text: 'Anda harus login terlebih dahulu untuk menyimpan pekerjaan.',
                showCancelButton: true,
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#14489b',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(
                        window.location.href);
                }
            });
            return;
        @endguest

        if (!jobId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Job ID tidak ditemukan. Silakan refresh halaman.',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        if (isSaved) {
            Swal.fire({
                title: 'Hapus dari Favorit?',
                html: `
                    <div class="text-start">
                        <p class="mb-2">Apakah Anda yakin ingin menghapus lowongan ini dari daftar favorit?</p>
                        <div class="alert alert-warning py-2 mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <small>Lowongan akan dihapus dari halaman <strong>Aktivitas</strong></small>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash me-1"></i>Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performUnsaveJobFromModal(jobId, $button, jobTitle);
                }
            });
        } else {
            Swal.fire({
                title: 'Simpan Lowongan?',
                html: `
                    <div class="text-start">
                        <p class="mb-2">Apakah Anda ingin menyimpan lowongan ini?</p>
                        <div class="alert alert-info py-2 mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            <small>Lowongan akan disimpan di halaman <strong>Aktivitas</strong></small>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-bookmark me-1"></i>Ya, Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    performSaveJobFromModal(jobId, $button, jobTitle);
                }
            });
        }
        });

        function performSaveJobFromModal(jobId, $button, jobTitle) {
            console.log('💾 Saving job, ID:', jobId);

            $button.prop('disabled', true);

            Swal.fire({
                title: 'Menyimpan...',
                html: '<div class="spinner-border text-warning"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ route('save.job-history') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_posting_id: jobId
                },
                success: function(response) {
                    console.log('✅ Save success:', response);

                    if (response.success) {
                        $button.removeClass('btn-outline-warning').addClass('btn-warning');
                        $button.find('i').removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                        $button.find('.save-text').text('Sudah Disimpan');
                        $button.data('is-saved', true);

                        if (!savedJobIdsGlobal.includes(jobId)) {
                            savedJobIdsGlobal.push(jobId);
                        }

                        const $listingIcon = $(`.job-card-clickable[data-job-id="${jobId}"]`).find(
                            '.bookmark-job-btn i');
                        if ($listingIcon.length > 0) {
                            $listingIcon.removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                            $listingIcon.css('color', '#ffc107');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Disimpan! 🎉',
                            text: `"${jobTitle}" ditambahkan ke favorit`,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                },
                error: function(xhr) {
                    console.error('❌ Save error:', xhr);

                    let errorMessage = 'Terjadi kesalahan saat menyimpan lowongan';

                    if (xhr.status === 401) {
                        errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
                    } else if (xhr.status === 400) {
                        errorMessage = 'Pekerjaan sudah disimpan sebelumnya.';
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                },
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        }

        function performUnsaveJobFromModal(jobId, $button, jobTitle) {
            console.log('🗑️ Unsaving job, ID:', jobId);

            $button.prop('disabled', true);

            Swal.fire({
                title: 'Menghapus...',
                html: '<div class="spinner-border text-danger"></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '{{ route('unsave.job-history') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    job_posting_id: jobId
                },
                success: function(response) {
                    console.log('✅ Unsave success:', response);

                    if (response.success) {
                        $button.removeClass('btn-warning').addClass('btn-outline-warning');
                        $button.find('i').removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                        $button.find('.save-text').text('Simpan');
                        $button.data('is-saved', false);

                        const index = savedJobIdsGlobal.indexOf(jobId);
                        if (index > -1) {
                            savedJobIdsGlobal.splice(index, 1);
                        }

                        const $listingIcon = $(`.job-card-clickable[data-job-id="${jobId}"]`).find(
                            '.bookmark-job-btn i');
                        if ($listingIcon.length > 0) {
                            $listingIcon.removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                            $listingIcon.css('color', '#6c757d');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Dihapus!',
                            text: `"${jobTitle}" dihapus dari favorit`,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                },
                error: function(xhr) {
                    console.error('❌ Unsave error:', xhr);

                    let errorMessage = 'Terjadi kesalahan saat menghapus lowongan';

                    if (xhr.status === 401) {
                        errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Pekerjaan tidak ditemukan di daftar simpanan.';
                    } else if (xhr.responseJSON?.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                },
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        }

        function updateJobsPagination(currentPage, totalPages) {
            const paginationContainer = $('#jobs-pagination-container');
            paginationContainer.empty();

            if (totalPages <= 1) return;

            let paginationHTML =
                '<nav aria-label="Jobs pagination"><ul class="pagination justify-content-center">';

            if (currentPage > 1) {
                paginationHTML +=
                    `<li class="page-item"><a class="page-link jobs-pagination-link" href="#" data-page="${currentPage - 1}">&laquo;</a></li>`;
            } else {
                paginationHTML += '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
            }

            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    paginationHTML +=
                        `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    paginationHTML +=
                        `<li class="page-item"><a class="page-link jobs-pagination-link" href="#" data-page="${i}">${i}</a></li>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    paginationHTML +=
                        '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            if (currentPage < totalPages) {
                paginationHTML +=
                    `<li class="page-item"><a class="page-link jobs-pagination-link" href="#" data-page="${currentPage + 1}">&raquo;</a></li>`;
            } else {
                paginationHTML += '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
            }

            paginationHTML += '</ul></nav>';
            paginationContainer.html(paginationHTML);
        }

        $(document).on('click', '.jobs-pagination-link', function(e) {
            e.preventDefault();
            var page = parseInt($(this).data('page'));
            currentJobsPage = page;
            renderJobsWithPagination(page);
            $('.modal-body').animate({
                scrollTop: 0
            }, 300);
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            var targetTab = $(e.target).attr('id');
            if (targetTab === 'lowongan-tab' && currentCompanyData) {
                console.log('Tab switched to Lowongan');
                currentJobsPage = 1;
                renderJobsWithPagination(1);
            }
        });

        function filterCompanies(page = 1) {
            const name = $('#name').val();
            let industry = $('#industry').val();
            const sortBy = $('#sortBy').val();

            const queryParams = $.param({
                name: name,
                industry: industry,
                sort_by: sortBy,
                page: page
            });

            $('#loading-spinner').fadeIn(200);
            $('#company-listings').fadeOut(200);
            $('#pagination-container').fadeOut(200);
            $('#filterBtn').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Mencari...');

            $.ajax({
                url: '{{ route('search.companies') }}?' + queryParams,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const companyListings = $('#company-listings');
                    companyListings.empty();

                    if (data.companies && data.companies.length > 0) {
                        data.companies.forEach(function(company) {
                            var openJobsCount = company.job_postings ? company.job_postings
                                .filter(
                                    job => job.status === 'Open').length : 0;
                            const companyCard = `
                            <div class="col-lg-6">
                                <div class="card company-card border rounded-3" style="cursor: pointer;"
                                    data-company-id="${company.id}" data-bs-toggle="modal" data-bs-target="#companyDetailModal">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-2">${company.name}</h5>
                                                <p class="text-muted mb-2">${company.industries ? company.industries.name : 'Tipe Industri'}</p>
                                                <p class="text-muted mb-0"><i class="bi bi-geo-alt me-1"></i>${company.location || 'Lokasi Perusahaan'}</p>
                                            </div>
                                            <div class="text-end ms-3">
                                                <span class="badge bg-secondary px-3 py-2">${openJobsCount} Jobs</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-star-fill text-warning"></i>
                                                <span class="fw-bold">${parseFloat(company.avg_rating || 0).toFixed(1)}</span>
                                                <span class="text-muted ms-2">Ulasan</span>
                                            </div>
                                            <a href="#" class="text-decoration-underline" onclick="event.stopPropagation();">See detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                            companyListings.append(companyCard);
                        });

                        if (data.pagination) {
                            updatePagination(data.pagination);
                        }
                    } else {
                        companyListings.html(`
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                                    <h5 class="text-muted mt-3">Tidak Ada Hasil</h5>
                                </div>
                            `);
                        $('#pagination-container').empty();
                    }

                    setTimeout(function() {
                        $('#loading-spinner').fadeOut(200);
                        $('#company-listings').fadeIn(400);
                        if (data.companies && data.companies.length > 0) {
                            $('#pagination-container').fadeIn(400);
                        }
                        $('#filterBtn').prop('disabled', false).html(
                            '<i class="bi bi-funnel me-2"></i>Cari Perusahaan');
                        $('html, body').animate({
                            scrollTop: $('#company-listings').offset().top - 100
                        }, 500);
                    }, 300);
                },
                error: function(error) {
                    $('#loading-spinner').fadeOut(200);
                    $('#company-listings').fadeIn(400);
                    $('#filterBtn').prop('disabled', false).html(
                        '<i class="bi bi-funnel me-2"></i>Cari Perusahaan');
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.responseJSON?.message ||
                            'Terjadi kesalahan saat mencari perusahaan.',
                    });
                }
            });
        }

        function updatePagination(pagination) {
            const paginationContainer = $('#pagination-container');
            paginationContainer.empty();

            if (pagination.last_page <= 1) return;

            let paginationHTML = '<nav><ul class="pagination justify-content-center">';

            if (pagination.current_page > 1) {
                paginationHTML +=
                    `<li class="page-item"><a class="page-link pagination-link" href="#" data-page="${pagination.current_page - 1}">&laquo;</a></li>`;
            } else {
                paginationHTML += '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
            }

            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    paginationHTML +=
                        `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 2 && i <=
                        pagination.current_page + 2)) {
                    paginationHTML +=
                        `<li class="page-item"><a class="page-link pagination-link" href="#" data-page="${i}">${i}</a></li>`;
                } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
                    paginationHTML +=
                        '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            if (pagination.current_page < pagination.last_page) {
                paginationHTML +=
                    `<li class="page-item"><a class="page-link pagination-link" href="#" data-page="${pagination.current_page + 1}">&raquo;</a></li>`;
            } else {
                paginationHTML += '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
            }

            paginationHTML += '</ul></nav>';
            paginationContainer.html(paginationHTML);
        }

        $('#filterBtn').click(function() {
            filterCompanies(1);
        });

        $(document).on('click', '.pagination-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            filterCompanies(page);
        });

        $('#name').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                filterCompanies(1);
            }
        });

        console.log('✅ All event listeners registered');
        });
    </script>
@endsection
