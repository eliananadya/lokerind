@extends('layouts.main')

@section('title', 'Buat Lowongan Baru')

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-blue: #14489b;
        --secondary-blue: #244770;
        --dark-blue: #1e3992;
        --light-blue: #dbeafe;
        --bg-blue: #eff6ff;
    }

    /* ✅ DISABLED INPUT STYLING */
    input[name*="[amount]"]:disabled {
        background-color: #e9ecef !important;
        cursor: not-allowed;
        opacity: 0.7;
    }

    input[name*="[amount]"]:disabled::placeholder {
        color: #6c757d;
        font-style: italic;
    }

    body {
        background-color: #f8f9fa;
    }

    .job-date-item {
        background: #f8f9fa;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .job-date-item:hover {
        border-color: var(--primary-blue);
        box-shadow: 0 2px 8px rgba(20, 72, 155, 0.1);
    }

    .remove-date-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .remove-date-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .benefit-item {
        background: #f8f9fa;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .benefit-item:hover {
        border-color: var(--primary-blue);
        box-shadow: 0 2px 8px rgba(20, 72, 155, 0.1);
    }

    .remove-benefit-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .remove-benefit-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    /* Header Styles */
    .page-header {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(20, 72, 155, 0.3);
    }

    .page-header h3 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    /* Form Section Styles */
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
    }

    .form-section:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .form-section h5 {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid var(--light-blue);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.25rem;
    }

    .form-section h5 i {
        font-size: 1.5rem;
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label .text-danger {
        font-size: 1.2rem;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(20, 72, 155, 0.15);
    }

    /* Select2 Customization */
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        min-height: 48px;
        padding: 0.25rem;
    }

    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(20, 72, 155, 0.15);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 0.5rem;
    }

    /* Button Styles */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 8px rgba(20, 72, 155, 0.2);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(20, 72, 155, 0.3);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-blue);
        color: var(--primary-blue);
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        background: var(--primary-blue);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(20, 72, 155, 0.2);
    }

    .btn-secondary {
        padding: 0.75rem 2rem;
        font-weight: 600;
    }

    /* Input Group Icons */
    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-blue);
        font-size: 1.2rem;
    }

    .input-with-icon input {
        padding-left: 3rem;
    }

    /* Progress Steps */
    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: #e5e7eb;
    }

    .progress-step.active .step-number {
        background: var(--primary-blue);
        color: white;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .step-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }

    /* Helper Text */
    .helper-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .helper-text i {
        font-size: 1rem;
    }

    /* Required Field Indicator */
    .required-fields-note {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .required-fields-note i {
        color: #f59e0b;
        font-size: 1.2rem;
    }
</style>
@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><i class="bi bi-plus-circle me-2"></i>Buat Lowongan Baru</h3>
                    <p class="mb-0 opacity-90">Isi formulir dengan lengkap untuk membuat lowongan pekerjaan</p>
                </div>
                <a href="{{ route('company.dashboard') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="progress-step active">
                <div class="step-number">1</div>
                <div class="step-label">Informasi Dasar</div>
            </div>
            <div class="progress-step">
                <div class="step-number">2</div>
                <div class="step-label">Persyaratan</div>
            </div>
            <div class="progress-step">
                <div class="step-number">3</div>
                <div class="step-label">Detail</div>
            </div>
            <div class="progress-step">
                <div class="step-number">4</div>
                <div class="step-label">Publikasi</div>
            </div>
        </div>

        <!-- Required Fields Note -->
        <div class="required-fields-note">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Catatan:</strong> Field yang ditandai dengan <span class="text-danger">*</span> wajib diisi
        </div>

        <form id="jobPostingForm">
            @csrf

            <!-- Informasi Dasar -->
            <div class="form-section">
                <h5><i class="bi bi-info-circle"></i>Informasi Dasar</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">
                            <i class="bi bi-briefcase text-primary"></i>
                            Judul Posisi <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Contoh: Marketing Manager" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="industries_id" class="form-label">
                            <i class="bi bi-building text-primary"></i>
                            Industri <span class="text-danger">*</span>
                        </label>
                        <select class="form-select select2-single" id="industries_id" name="industries_id" required>
                            <option value="">Pilih Industri</option>
                            @foreach ($industries as $industry)
                                <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">
                            <i class="bi bi-file-text text-primary"></i>
                            Deskripsi Pekerjaan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="5"
                            placeholder="Jelaskan tugas dan tanggung jawab posisi ini..." required></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type_jobs_id" class="form-label">
                            <i class="bi bi-clock text-primary"></i>
                            Tipe Pekerjaan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select select2-single" id="type_jobs_id" name="type_jobs_id" required>
                            <option value="">Pilih Tipe Pekerjaan</option>
                            @foreach ($typeJobs as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="cities_id" class="form-label">
                            <i class="bi bi-geo-alt text-primary"></i>
                            Lokasi <span class="text-danger">*</span>
                        </label>
                        <select class="form-select select2-single" id="cities_id" name="cities_id" required>
                            <option value="">Pilih Kota</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="address" class="form-label">
                            <i class="bi bi-pin-map text-primary"></i>
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Contoh: Jl. Sudirman No.123" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="salary" class="form-label">
                            <i class="bi bi-cash text-primary"></i>
                            Gaji (Rp) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="salary" name="salary" min="0"
                            placeholder="5000000" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type_salary" class="form-label">
                            <i class="bi bi-calendar-check text-primary"></i>
                            Tipe Gaji <span class="text-danger">*</span>
                        </label>
                        <select name="type_salary" class="form-control" id="type_salary" required>
                            <option value="">Pilih Tipe Gaji</option>
                            <option value="total">Total</option>
                            <option value="shift">Shift</option>
                        </select>
                        <div class="helper-text">
                            <i class="bi bi-info-circle"></i>
                            Total = gaji keseluruhan, Shift = gaji per shift
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="slot" class="form-label">
                            <i class="bi bi-people text-primary"></i>
                            Jumlah Posisi Tersedia <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="slot" name="slot" value="1"
                            min="1" required>
                    </div>
                </div>
            </div>

            <!-- Persyaratan Kandidat -->
            <div class="form-section">
                <h5><i class="bi bi-person-check"></i>Persyaratan Kandidat</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="gender" class="form-label">
                            <i class="bi bi-gender-ambiguous text-primary"></i>
                            Jenis Kelamin <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="All">Semua</option>
                            <option value="Male">Laki-laki</option>
                            <option value="Female">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="min_age" class="form-label">
                            <i class="bi bi-calendar-check text-primary"></i>
                            Usia Minimum <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="min_age" name="min_age" value="17"
                            min="17" max="100" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="max_age" class="form-label">
                            <i class="bi bi-calendar-x text-primary"></i>
                            Usia Maksimum <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="max_age" name="max_age" value="60"
                            min="17" max="100" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="min_height" class="form-label">
                            <i class="bi bi-rulers text-primary"></i>
                            Tinggi Min (cm) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="min_height" name="min_height" value="150"
                            min="100" max="250" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="min_weight" class="form-label">
                            <i class="bi bi-speedometer text-primary"></i>
                            Berat Min (kg) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="min_weight" name="min_weight" value="40"
                            min="30" max="200" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="level_english" class="form-label">
                            <i class="bi bi-translate text-primary"></i>
                            Level Bahasa Inggris <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="level_english" name="level_english" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="beginner">Beginner (Pemula)</option>
                            <option value="intermediate">Intermediate (Menengah)</option>
                            <option value="expert">Expert (Ahli)</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="level_mandarin" class="form-label">
                            <i class="bi bi-translate text-primary"></i>
                            Level Bahasa Mandarin <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="level_mandarin" name="level_mandarin" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="beginner">Beginner (Pemula)</option>
                            <option value="intermediate">Intermediate (Menengah)</option>
                            <option value="expert">Expert (Ahli)</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="has_interview" class="form-label">
                            <i class="bi bi-camera-video text-primary"></i>
                            Wawancara <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="has_interview" name="has_interview" required>
                            <option value="0">Tidak Ada</option>
                            <option value="1">Ada</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Keterampilan -->
            <div class="form-section">
                <h5><i class="bi bi-tools"></i>Keterampilan yang Dibutuhkan</h5>
                <select class="form-select select2-multiple" id="skills" name="skills[]" multiple>
                    @foreach ($skills as $skill)
                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Benefit -->
            <div class="form-section">
                <h5><i class="bi bi-gift"></i>Benefit & Fasilitas <small class="text-muted">(Opsional)</small></h5>

                <div id="benefitsContainer">
                    <div class="benefit-item" data-index="0">
                        <button type="button" class="remove-benefit-btn" onclick="removeBenefit(0)"
                            style="display: none;">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-gift text-primary"></i>
                                    Benefit
                                </label>
                                <select class="form-select select2-benefit" name="benefits[0][benefit_id]">
                                    <option value="">Pilih Benefit</option>
                                    @foreach ($benefits as $benefit)
                                        <option value="{{ $benefit->id }}">{{ $benefit->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-tag text-primary"></i>
                                    Tipe Benefit
                                </label>
                                <select class="form-select benefit-type-select" name="benefits[0][benefit_type]">
                                    <option value="">Pilih Tipe</option>
                                    <option value="in kind">In Kind (Barang)</option>
                                    <option value="cash">Cash (Uang)</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-cash-stack text-primary"></i>
                                    Jumlah/Nominal
                                </label>
                                <input type="text" class="form-control benefit-amount-input"
                                    name="benefits[0][amount]" placeholder="Contoh: 500000 atau 1 unit">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary mt-2" onclick="addBenefit()">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Benefit
                </button>

                <div class="helper-text mt-2">
                    <i class="bi bi-lightbulb"></i>
                    Benefit yang menarik akan meningkatkan minat kandidat
                </div>
            </div>

            <!-- Jadwal Kerja -->
            <div class="form-section">
                <h5><i class="bi bi-calendar-week"></i>Jadwal Kerja</h5>

                <div id="jobDatesContainer">
                    <div class="job-date-item" data-index="0">
                        <button type="button" class="remove-date-btn" onclick="removeJobDate(0)"
                            style="display: none;">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-calendar-day text-primary"></i>
                                    Hari <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control day-display" name="job_dates[0][day_display]"
                                    readonly placeholder="Pilih tanggal dulu"
                                    style="background-color: #e9ecef; cursor: not-allowed;">
                                <input type="hidden" name="job_dates[0][day_id]" class="day-id-input">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                    Tanggal <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control date-input" name="job_dates[0][date]" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-clock text-primary"></i>
                                    Jam Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="time" class="form-control" name="job_dates[0][start_time]" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-clock-fill text-primary"></i>
                                    Jam Selesai <span class="text-danger">*</span>
                                </label>
                                <input type="time" class="form-control" name="job_dates[0][end_time]" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary mt-2" onclick="addJobDate()">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Jadwal
                </button>

                <div class="helper-text mt-2">
                    <i class="bi bi-lightbulb"></i>
                    Hari akan otomatis terisi saat Anda memilih tanggal
                </div>
            </div>

            <!-- Periode Rekrutmen -->
            <div class="form-section">
                <h5><i class="bi bi-calendar-range"></i>Periode Rekrutmen</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="open_recruitment" class="form-label">
                            <i class="bi bi-calendar-plus text-primary"></i>
                            Tanggal Buka <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="open_recruitment" name="open_recruitment"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="close_recruitment" class="form-label">
                            <i class="bi bi-calendar-x text-primary"></i>
                            Tanggal Tutup <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="close_recruitment" name="close_recruitment"
                            required>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end mb-4 gap-3">
                <a href="{{ route('company.jobs.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg me-2"></i>Batal
                </a>
                <button type="submit" name="action" value="draft" class="btn btn-outline-primary" id="saveDraftBtn">
                    <i class="bi bi-save me-2"></i>Simpan sebagai Draft
                </button>
                <button type="submit" name="action" value="publish" class="btn btn-primary-custom text-white"
                    id="publishBtn">
                    <i class="bi bi-check-lg me-2 text-white"></i>Publikasikan Lowongan
                </button>
            </div>
        </form>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let benefitIndex = 1;
    let jobDateIndex = 1;

    // ✅ DAY MAPPING: passed from controller as $dayMapping
    // Format: { 'senin': { id: '1', name: 'Monday' }, ... }
    const dayMapping = @json($dayMapping);

    // ✅ JS getDay() index → nama hari Indonesia
    // getDay(): 0=Sunday, 1=Monday, 2=Tuesday, ..., 6=Saturday
    const dayNames = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

    // ✅ CORE FUNCTION: given a date string (YYYY-MM-DD), resolve day_id + display name
    function resolveDayFromDate(dateString) {
        // Tambahkan T00:00:00 biar gak kena timezone shift
        const date = new Date(dateString + 'T00:00:00');
        const jsDay = date.getDay(); // 0–6
        const dayNameIndo = dayNames[jsDay]; // misal 'rabu'
        const dayData = dayMapping[dayNameIndo]; // misal { id: '3', name: 'Wednesday' }

        return dayData || null;
    }

    $(document).ready(function() {
        // Initialize Select2
        $('.select2-single').select2({
            width: '100%',
            placeholder: 'Pilih...',
            allowClear: true
        });

        $('.select2-multiple').select2({
            width: '100%',
            placeholder: 'Pilih satu atau lebih...',
            allowClear: true
        });

        $('.select2-benefit').select2({
            width: '100%',
            placeholder: 'Pilih Benefit...',
            allowClear: true
        });

        // Date validation — open_recruitment min = hari ini
        const today = new Date().toISOString().split('T')[0];
        $('#open_recruitment').attr('min', today);

        $('#open_recruitment').on('change', function() {
            $('#close_recruitment').attr('min', $(this).val());
        });

        // ✅ Set min date untuk semua date-input yang sudah ada di DOM
        $('.date-input').attr('min', today);

        // ✅ DELEGATED EVENT: tangkap change pada .date-input (termasuk yang ditambahkan later)
        $(document).on('change', '.date-input', function() {
            const $dateInput = $(this);
            const $container = $dateInput.closest('.job-date-item');
            const $dayDisplay = $container.find('.day-display');
            const $dayIdInput = $container.find('.day-id-input');

            const dateValue = $dateInput.val();
            if (!dateValue) return;

            const dayData = resolveDayFromDate(dateValue);

            if (dayData) {
                $dayDisplay.val(dayData.name);
                $dayIdInput.val(dayData.id);
            } else {
                $dayDisplay.val('');
                $dayIdInput.val('');
            }
        });

        // ✅ FORM SUBMIT
        $('#jobPostingForm').on('submit', function(e) {
            e.preventDefault();

            const clickedButton = $(document.activeElement);
            const action = clickedButton.val();
            const status = action === 'publish' ? 'Open' : 'Draft';

            Swal.fire({
                title: action === 'publish' ? 'Publikasikan Lowongan?' :
                    'Simpan sebagai Draft?',
                html: action === 'publish' ?
                    'Lowongan akan langsung dipublikasikan dan dapat dilihat oleh kandidat.' :
                    'Lowongan akan disimpan sebagai draft dan tidak akan terlihat oleh kandidat.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: action === 'publish' ?
                    '<i class="bi bi-check-lg me-1"></i> Ya, Publikasikan' :
                    '<i class="bi bi-save me-1"></i> Ya, Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: action === 'publish' ? '#28a745' : '#0d6efd',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm(status);
                }
            });
        });

        // ✅ INITIALIZE: benefit type check on page load
        $('select[name*="[benefit_type]"]').each(function() {
            const $typeSelect = $(this);
            const $amountInput = $typeSelect.closest('.row').find('input[name*="[amount]"]');

            if ($typeSelect.val() === 'in kind') {
                $amountInput.prop('disabled', true).val('');
                $amountInput.attr('placeholder', 'Tidak diperlukan untuk In Kind');
                $amountInput.css('background-color', '#e9ecef');
            }
        });
    });

    // --------- SUBMIT ---------
    function submitForm(status) {
        const formData = new FormData($('#jobPostingForm')[0]);
        formData.append('status', status);

        $.ajax({
            url: '{{ route('company.jobs.store') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `<p>${response.message}</p>`,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route('company.jobs.index') }}';
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Gagal menyimpan lowongan';
                let errorDetails = '';

                if (xhr.responseJSON) {
                    errorMessage = xhr.responseJSON.message || errorMessage;

                    if (xhr.responseJSON.errors) {
                        errorDetails = '<ul class="text-start mt-2">';
                        Object.values(xhr.responseJSON.errors).forEach(function(error) {
                            errorDetails += `<li>${error[0]}</li>`;
                        });
                        errorDetails += '</ul>';
                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    html: errorMessage + errorDetails,
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }

    // --------- BENEFIT: ADD / REMOVE ---------
    function addBenefit() {
        const container = $('#benefitsContainer');
        const newBenefit = `
        <div class="benefit-item" data-index="${benefitIndex}">
            <button type="button" class="remove-benefit-btn" onclick="removeBenefit(${benefitIndex})">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="bi bi-gift text-primary"></i>
                        Benefit
                    </label>
                    <select class="form-select select2-benefit" name="benefits[${benefitIndex}][benefit_id]">
                        <option value="">Pilih Benefit</option>
                        @foreach ($benefits as $benefit)
                            <option value="{{ $benefit->id }}">{{ $benefit->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="bi bi-tag text-primary"></i>
                        Tipe Benefit
                    </label>
                    <select class="form-select benefit-type-select" name="benefits[${benefitIndex}][benefit_type]">
                        <option value="">Pilih Tipe</option>
                        <option value="in kind">In Kind (Barang)</option>
                        <option value="cash">Cash (Uang)</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        <i class="bi bi-cash-stack text-primary"></i>
                        Jumlah/Nominal
                    </label>
                    <input type="text" class="form-control benefit-amount-input"
                        name="benefits[${benefitIndex}][amount]"
                        placeholder="Contoh: 500000 atau 1 unit">
                </div>
            </div>
        </div>`;

        container.append(newBenefit);

        $(`select[name="benefits[${benefitIndex}][benefit_id]"]`).select2({
            width: '100%',
            placeholder: 'Pilih Benefit...',
            allowClear: true
        });

        if ($('.benefit-item').length > 1) {
            $('.benefit-item .remove-benefit-btn').show();
        }

        benefitIndex++;
    }

    function removeBenefit(index) {
        $(`.benefit-item[data-index="${index}"]`).remove();
        if ($('.benefit-item').length === 1) {
            $('.benefit-item .remove-benefit-btn').hide();
        }
    }

    // --------- JOB DATE: ADD / REMOVE ---------
    function addJobDate() {
        const today = new Date().toISOString().split('T')[0];
        const container = $('#jobDatesContainer');
        const newJobDate = `
            <div class="job-date-item" data-index="${jobDateIndex}">
                <button type="button" class="remove-date-btn" onclick="removeJobDate(${jobDateIndex})">
                    <i class="bi bi-x-lg"></i>
                </button>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-day text-primary"></i>
                            Hari <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control day-display"
                               name="job_dates[${jobDateIndex}][day_display]"
                               readonly
                               placeholder="Pilih tanggal dulu"
                               style="background-color: #e9ecef; cursor: not-allowed;">
                        <input type="hidden" name="job_dates[${jobDateIndex}][day_id]" class="day-id-input">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-event text-primary"></i>
                            Tanggal <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control date-input"
                               name="job_dates[${jobDateIndex}][date]"
                               min="${today}"
                               required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="bi bi-clock text-primary"></i>
                            Jam Mulai <span class="text-danger">*</span>
                        </label>
                        <input type="time" class="form-control" name="job_dates[${jobDateIndex}][start_time]" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="bi bi-clock-fill text-primary"></i>
                            Jam Selesai <span class="text-danger">*</span>
                        </label>
                        <input type="time" class="form-control" name="job_dates[${jobDateIndex}][end_time]" required>
                    </div>
                </div>
            </div>`;

        container.append(newJobDate);

        if ($('.job-date-item').length > 1) {
            $('.job-date-item .remove-date-btn').show();
        }

        jobDateIndex++;
    }

    function removeJobDate(index) {
        $(`.job-date-item[data-index="${index}"]`).remove();
        if ($('.job-date-item').length === 1) {
            $('.job-date-item .remove-date-btn').hide();
        }
    }

    // --------- BENEFIT TYPE CHANGE: toggle amount input ---------
    $(document).on('change', 'select[name*="[benefit_type]"]', function() {
        const $typeSelect = $(this);
        const $amountInput = $typeSelect.closest('.row').find('input[name*="[amount]"]');
        const selectedType = $typeSelect.val();

        if (selectedType === 'in kind') {
            $amountInput.prop('disabled', true).val('');
            $amountInput.attr('placeholder', 'Tidak diperlukan untuk In Kind');
            $amountInput.css('background-color', '#e9ecef');
        } else if (selectedType === 'cash') {
            $amountInput.prop('disabled', false);
            $amountInput.attr('placeholder', 'Contoh: 500000');
            $amountInput.css('background-color', '#ffffff');
        } else {
            $amountInput.prop('disabled', false).val('');
            $amountInput.attr('placeholder', 'Contoh: 500000 atau 1 unit');
            $amountInput.css('background-color', '#ffffff');
        }
    });
</script>
