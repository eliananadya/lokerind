@extends('layouts.main')

@section('title')
    Home
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="min-height: 500px; background: linear-gradient(135deg, var(--bg-blue) 0%, #ffffff 100%);">
        <div class="container h-100">
            <div class="row align-items-center h-100 py-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4" style="color: var(--primary-blue);">
                        Temukan Pekerjaan Impian Anda
                    </h1>
                    <p class="lead text-muted mb-4">
                        Platform terpercaya yang menghubungkan pencari kerja dengan perusahaan terbaik di Indonesia. Raih
                        karir cemerlang bersama kami!
                    </p>
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        @auth
                            @if (Auth::user()->hasRole('user'))
                                {{-- Candidate --}}
                                <a href="{{ route('jobs.index') }}" class="btn btn-primary-custom px-4 py-2 text-white">
                                    <i class="bi bi-search me-2"></i>Cari Lowongan
                                </a>
                            @endif
                        @else
                            {{-- Guest --}}
                            <a href="{{ route('jobs.index') }}" class="btn btn-primary-custom text-center px-4 py-2 text-white">
                                <i class="bi bi-search me-2"></i>Cari Lowongan
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary text-center px-4 py-2">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/cariKaryawan.png') }}" alt="Job Search Image"
                            class="img-fluid rounded-4 shadow-lg" style="max-height: 450px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section class="py-5" id="about">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Tentang Kami</h2>
                <p class="text-muted">Kami hadir sebagai solusi modern untuk menghubungkan talenta terbaik dengan perusahaan
                    yang berkembang pesat</p>
            </div>
            <div class="row g-4">
                <!-- Kelebihan 1 -->
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-2 shadow-sm"
                        style="border-color: var(--primary-blue) !important; border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px; background-color: var(--bg-blue); color: var(--primary-blue);">
                                    <h3 class="fw-bold mb-0">01</h3>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-3" style="color: var(--primary-blue);">
                                Proses Cepat & Mudah
                            </h5>
                            <p class="text-muted mb-0">
                                Daftar dan lamar pekerjaan hanya dalam hitungan menit dengan sistem yang user-friendly
                                dan efisien
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Kelebihan 2 -->
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-2 shadow-sm"
                        style="border-color: var(--primary-blue) !important; border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px; background-color: var(--bg-blue); color: var(--primary-blue);">
                                    <h3 class="fw-bold mb-0">02</h3>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-3" style="color: var(--primary-blue);">
                                Ribuan Lowongan Kerja
                            </h5>
                            <p class="text-muted mb-0">
                                Akses ke ribuan lowongan kerja dari berbagai industri dan perusahaan ternama
                                se-Indonesia
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Kelebihan 3 -->
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-2 shadow-sm"
                        style="border-color: var(--primary-blue) !important; border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px; background-color: var(--bg-blue); color: var(--primary-blue);">
                                    <h3 class="fw-bold mb-0">03</h3>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-3" style="color: var(--primary-blue);">
                                Lowongan Terverifikasi
                            </h5>
                            <p class="text-muted mb-0">
                                Semua lowongan telah melalui proses verifikasi ketat untuk menjamin keamanan dan
                                kredibilitas
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Kelebihan 4 -->
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-2 shadow-sm"
                        style="border-color: var(--primary-blue) !important; border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px; background-color: var(--bg-blue); color: var(--primary-blue);">
                                    <h3 class="fw-bold mb-0">04</h3>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-3" style="color: var(--primary-blue);">
                                Notifikasi Real-time
                            </h5>
                            <p class="text-muted mb-0">
                                Dapatkan update langsung tentang status lamaran
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: var(--bg-blue);">
        <div class="container">
            <div class="row align-items-center g-4">
                <!-- Left Column - CTA -->
                <div class="col-lg-6">
                    <div class="p-4 border-2 rounded-3 bg-white shadow"
                        style="border-color: var(--primary-blue) !important;">
                        <h2 class="fw-bold mb-3" style="color: var(--primary-blue);">
                            Ingin Merekrut Karyawan ?
                        </h2>
                        <p class="text-muted mb-4">
                            Bergabunglah dengan ratusan perusahaan yang telah mempercayai platform kami untuk menemukan
                            talenta berkualitas.
                            Akses database kandidat terverifikasi, sistem manajemen rekrutmen yang efisien, dan dukungan
                            tim profesional kami.
                        </p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-primary-custom me-2"></i>
                                <span>Posting lowongan</span>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-primary-custom me-2"></i>
                                <span>Filter kandidat</span>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-primary-custom me-2"></i>
                                <span>Dashboard analytics lengkap</span>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-primary-custom me-2"></i>
                                <span>Customer support 24/7</span>
                            </li>
                        </ul>
                        @auth
                            @if (Auth::user()->hasRole('user'))
                                {{-- User login --}}
                                <a href="{{ route('companies.index') }}" class="btn btn-primary-custom px-4 py-2 text-white">
                                    <i class="bi bi-buildings me-2"></i>Lihat Perusahaan
                                </a>
                            @endif
                        @else
                            {{-- Guest --}}
                            <a href="{{ route('register') }}" class="btn btn-primary-custom px-4 py-2 text-white">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sebagai Perusahaan
                            </a>
                        @endauth
                    </div>
                </div>
                <!-- Right Column - Image -->
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/rekrutmen.png') }}" alt="Recruitment"
                            class="img-fluid rounded-4 shadow-lg" style="max-height: 450px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
