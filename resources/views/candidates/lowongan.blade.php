@extends('layouts.main')

@section('content')
    <!-- Search Section -->
    <section class="py-5" style="background-color: var(--bg-blue);">
        <div class="container">
            <div class="row g-3">
                <!-- Search Job Title -->
                <div class="col-lg-3 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search Job Title">
                    </div>
                </div>

                <!-- City Dropdown -->
                <div class="col-lg-2 col-md-6">
                    <select class="form-select">
                        <option selected>City</option>
                        <option value="jakarta">Jakarta</option>
                        <option value="surabaya">Surabaya</option>
                        <option value="bandung">Bandung</option>
                        <option value="medan">Medan</option>
                        <option value="semarang">Semarang</option>
                    </select>
                </div>

                <!-- Type Job Dropdown -->
                <div class="col-lg-2 col-md-6">
                    <select class="form-select">
                        <option selected>Type Job</option>
                        <option value="full-time">Full Time</option>
                        <option value="part-time">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="internship">Internship</option>
                        <option value="freelance">Freelance</option>
                    </select>
                </div>

                <!-- Industries Dropdown -->
                <div class="col-lg-2 col-md-6">
                    <select class="form-select">
                        <option selected>Industries</option>
                        <option value="teknologi">Teknologi</option>
                        <option value="finance">Finance</option>
                        <option value="healthcare">Healthcare</option>
                        <option value="education">Education</option>
                        <option value="retail">Retail</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="col-lg-3 col-md-12">
                    <button class="btn btn-primary-custom w-100 text-white">
                        <i class="bi bi-funnel me-2 "></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Listings Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="fw-bold mb-4">Lowongan yang Direkomendasikan</h2>

            <div class="row g-4">
                <!-- Job Card 1 -->
                <div class="col-lg-6">
                    <div class="card h-100 border-2 shadow-sm"
                        style="border-color: var(--primary-blue) !important; border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold mb-2">Judul Pekerjaan</h5>
                                    <p class="text-muted mb-0">Nama perusahaan</p>
                                </div>
                                <small class="text-muted">Terakhir diperbarui xx Bulan</small>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-light text-dark me-2">Tipe Pekerjaan</span>
                                <span class="badge bg-light text-dark">Tipe Industri</span>
                            </div>

                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-2"></i>Lokasi Pekerjaan
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">Rp 250.000</h4>
                                    <small class="text-muted">Tanggal Event</small>
                                </div>
                                <div class="d-flex align-items-center gap-3 mt-4">
                                    <small class="text-muted">Slot : 2</small>
                                    <button class="btn btn-link p-0 text-muted">
                                        <i class="bi bi-bookmark" style="font-size: 1.5rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
    </section>
@endsection

@push('css')
    <style>
        .input-group-text {
            border-radius: 8px 0 0 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        .badge {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border: 1px solid #dee2e6;
        }
    </style>
@endpush
