@extends('layouts.main')

@section('title', 'Edit Lowongan')

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-blue: #14489b;
        --secondary-blue: #244770;
        --dark-blue: #1e3992;
        --light-blue: #dbeafe;
        --bg-blue: #eff6ff;
    }

    body {
        background-color: #f8f9fa;
    }

    /* Header Styles */
    .page-header {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
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
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.2);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-secondary {
        padding: 0.75rem 2rem;
        font-weight: 600;
    }

    /* Status Info Box */
    .status-info-box {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-left: 4px solid #f59e0b;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .status-info-box h6 {
        color: #92400e;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .status-info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(146, 64, 14, 0.2);
    }

    .status-info-item:last-child {
        border-bottom: none;
    }

    .status-label {
        font-weight: 600;
        color: #78350f;
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

    /* ✅ NEW: Job Dates & Benefits Styling */
    .job-date-item,
    .benefit-item {
        background: #f8f9fa;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .job-date-item:hover,
    .benefit-item:hover {
        border-color: var(--primary-blue);
        box-shadow: 0 2px 8px rgba(20, 72, 155, 0.1);
    }

    .remove-date-btn,
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

    .remove-date-btn:hover,
    .remove-benefit-btn:hover {
        background: #c82333;
        transform: scale(1.1);
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

    /* ✅ BENEFIT ITEM TRANSITION */
    .benefit-item {
        transition: all 0.3s ease;
    }
</style>

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><i class="bi bi-pencil-square me-2"></i>Edit Lowongan</h3>
                    <p class="mb-0 opacity-90">Perbarui informasi lowongan: <strong>{{ $job->title }}</strong></p>
                </div>
                <a href="{{ route('company.jobs.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Status Info Box -->
        <div class="status-info-box">
            <h6><i class="bi bi-info-circle me-2"></i>Informasi Status Lowongan</h6>
            <div class="status-info-item">
                <span class="status-label">Status Saat Ini:</span>
                <span>
                    @if ($job->status == 'Open')
                        <span class="badge bg-success">Open</span>
                    @elseif($job->status == 'Closed')
                        <span class="badge bg-danger">Closed</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </span>
            </div>
            <div class="status-info-item">
                <span class="status-label">Verifikasi:</span>
                <span>
                    @if ($job->verification_status == 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($job->verification_status == 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </span>
            </div>
            <div class="status-info-item">
                <span class="status-label">Total Pelamar:</span>
                <span class="badge bg-primary">{{ $job->applications->count() }} pelamar</span>
            </div>
            <div class="status-info-item">
                <span class="status-label">Terakhir Diupdate:</span>
                <span>{{ $job->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <form id="jobEditForm">
            @csrf
            @method('PUT')

            <!-- Informasi Dasar -->
            <div class="form-section">
                <h5><i class="bi bi-info-circle"></i>Informasi Dasar</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">
                            <i class="bi bi-briefcase text-primary"></i>
                            Judul Posisi <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $job->title }}"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="industries_id" class="form-label">
                            <i class="bi bi-building text-primary"></i>
                            Industri <span class="text-danger">*</span>
                        </label>
                        <select class="form-select select2-single" id="industries_id" name="industries_id" required>
                            <option value="">Pilih Industri</option>
                            @foreach ($industries as $industry)
                                <option value="{{ $industry->id }}"
                                    {{ $job->industries_id == $industry->id ? 'selected' : '' }}>
                                    {{ $industry->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">
                            <i class="bi bi-file-text text-primary"></i>
                            Deskripsi Pekerjaan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ $job->description }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type_jobs_id" class="form-label">
                            <i class="bi bi-clock text-primary"></i>
                            Tipe Pekerjaan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select select2-single" id="type_jobs_id" name="type_jobs_id" required>
                            <option value="">Pilih Tipe Pekerjaan</option>
                            @foreach ($typeJobs as $type)
                                <option value="{{ $type->id }}"
                                    {{ $job->type_jobs_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
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
                                <option value="{{ $city->id }}" {{ $job->cities_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="address" class="form-label">
                            <i class="bi bi-pin-map text-primary"></i>
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ $job->address }}" required>
                    </div>

                    {{-- ✅ REVISI 2: SALARY + TYPE SALARY --}}
                    <div class="col-md-4 mb-3">
                        <label for="salary" class="form-label">
                            <i class="bi bi-cash text-primary"></i>
                            Gaji (Rp) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="salary" name="salary"
                            value="{{ $job->salary }}" min="0" required>
                        <div class="helper-text">
                            <i class="bi bi-info-circle"></i>
                            Saat ini: Rp {{ number_format($job->salary, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type_salary" class="form-label">
                            <i class="bi bi-calendar-check text-primary"></i>
                            Tipe Gaji <span class="text-danger">*</span>
                        </label>
                        <select name="type_salary" class="form-control" id="type_salary" required>
                            <option value="">Pilih Tipe Gaji</option>
                            <option value="total" {{ $job->type_salary == 'total' ? 'selected' : '' }}>Total</option>
                            <option value="shift" {{ $job->type_salary == 'shift' ? 'selected' : '' }}>Shift
                            </option>
                        </select>
                        <div class="helper-text">
                            <i class="bi bi-info-circle"></i>
                            Total = gaji keseluruhan, Shift = gaji per shift
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="slot" class="form-label">
                            <i class="bi bi-people text-primary"></i>
                            Jumlah Posisi <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="slot" name="slot"
                            value="{{ $job->slot }}" min="1" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="status" class="form-label">
                            <i class="bi bi-toggle-on text-primary"></i>
                            Status Lowongan <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Draft" {{ $job->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Open" {{ $job->status == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Closed" {{ $job->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
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
                            <option value="All" {{ $job->gender == 'All' ? 'selected' : '' }}>Semua</option>
                            <option value="Male" {{ $job->gender == 'Male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Female" {{ $job->gender == 'Female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="min_age" class="form-label">
                            <i class="bi bi-calendar-check text-primary"></i>
                            Usia Minimum <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="min_age" name="min_age"
                            value="{{ $job->min_age }}" min="17" max="100" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="max_age" class="form-label">
                            <i class="bi bi-calendar-x text-primary"></i>
                            Usia Maksimum <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="max_age" name="max_age"
                            value="{{ $job->max_age }}" min="17" max="100" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="min_height" class="form-label">
                            <i class="bi bi-rulers text-primary"></i>
                            Tinggi Min (cm) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="min_height" name="min_height"
                            value="{{ $job->min_height }}" min="100" max="250" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="min_weight" class="form-label">
                            <i class="bi bi-speedometer text-primary"></i>
                            Berat Min (kg) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="min_weight" name="min_weight"
                            value="{{ $job->min_weight }}" min="30" max="200" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="level_english" class="form-label">
                            <i class="bi bi-translate text-primary"></i>
                            Level Bahasa Inggris <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="level_english" name="level_english" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="beginner" {{ $job->level_english == 'beginner' ? 'selected' : '' }}>Beginner
                                (Pemula)</option>
                            <option value="intermediate" {{ $job->level_english == 'intermediate' ? 'selected' : '' }}>
                                Intermediate (Menengah)</option>
                            <option value="expert" {{ $job->level_english == 'expert' ? 'selected' : '' }}>Expert (Ahli)
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="level_mandarin" class="form-label">
                            <i class="bi bi-translate text-primary"></i>
                            Level Bahasa Mandarin <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="level_mandarin" name="level_mandarin" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="beginner" {{ $job->level_mandarin == 'beginner' ? 'selected' : '' }}>Beginner
                                (Pemula)</option>
                            <option value="intermediate" {{ $job->level_mandarin == 'intermediate' ? 'selected' : '' }}>
                                Intermediate (Menengah)</option>
                            <option value="expert" {{ $job->level_mandarin == 'expert' ? 'selected' : '' }}>Expert (Ahli)
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="has_interview" class="form-label">
                            <i class="bi bi-camera-video text-primary"></i>
                            Wawancara <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="has_interview" name="has_interview" required>
                            <option value="0" {{ $job->has_interview == 0 ? 'selected' : '' }}>Tidak Ada</option>
                            <option value="1" {{ $job->has_interview == 1 ? 'selected' : '' }}>Ada</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Keterampilan -->
            <div class="form-section">
                <h5><i class="bi bi-tools"></i>Keterampilan yang Dibutuhkan</h5>
                <select class="form-select select2-multiple" id="skills" name="skills[]" multiple>
                    @php
                        $selectedSkills = $job->skills->pluck('id')->toArray();
                    @endphp
                    @foreach ($skills as $skill)
                        <option value="{{ $skill->id }}"
                            {{ in_array($skill->id, $selectedSkills) ? 'selected' : '' }}>
                            {{ $skill->name }}
                        </option>
                    @endforeach
                </select>
                <div class="helper-text mt-2">
                    <i class="bi bi-info-circle"></i>
                    Terpilih: {{ count($selectedSkills) }} keterampilan
                </div>
            </div>

            {{-- ✅ REVISI 1: BENEFIT WITH TYPE & AMOUNT --}}
            <div class="form-section">
                <h5><i class="bi bi-gift"></i>Benefit & Fasilitas</h5>

                <div id="benefitsContainer">
                    @php
                        $existingBenefits = $job->benefits; // Relasi dari JobPostingBenefit
                    @endphp
                    @if ($existingBenefits->count() > 0)
                        @foreach ($existingBenefits as $index => $jobBenefit)
                            <div class="benefit-item" data-index="{{ $index }}">
                                <button type="button" class="remove-benefit-btn"
                                    onclick="removeBenefit({{ $index }})"
                                    style="{{ $index == 0 && $existingBenefits->count() == 1 ? 'display: none;' : '' }}">
                                    <i class="bi bi-x-lg"></i>
                                </button>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-gift text-primary"></i>
                                            Benefit
                                        </label>
                                        <select class="form-select select2-benefit benefit-select"
                                            name="benefits[{{ $index }}][benefit_id]">
                                            <option value="">Pilih Benefit</option>
                                            @foreach ($benefits as $benefit)
                                                <option value="{{ $benefit->id }}"
                                                    {{ $jobBenefit->benefit_id == $benefit->id ? 'selected' : '' }}>
                                                    {{ $benefit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-tag text-primary"></i>
                                            Tipe Benefit
                                        </label>
                                        <select class="form-select benefit-type-select"
                                            name="benefits[{{ $index }}][benefit_type]">
                                            <option value="">Pilih Tipe</option>
                                            <option value="in kind"
                                                {{ $jobBenefit->benefit_type == 'in kind' ? 'selected' : '' }}>In Kind
                                                (Barang)
                                            </option>
                                            <option value="cash"
                                                {{ $jobBenefit->benefit_type == 'cash' ? 'selected' : '' }}>Cash (Uang)
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-cash-stack text-primary"></i>
                                            Jumlah/Nominal
                                        </label>
                                        <input type="text" class="form-control benefit-amount-input"
                                            name="benefits[{{ $index }}][amount]"
                                            value="{{ $jobBenefit->amount }}" placeholder="Contoh: 500000 atau 1 unit">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" class="btn btn-outline-primary mt-2" onclick="addBenefit()">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Benefit
                </button>

                <div class="helper-text mt-2">
                    <i class="bi bi-lightbulb"></i>
                    Benefit menarik akan meningkatkan minat kandidat
                </div>
            </div>

            {{-- ✅ REVISI 3: MULTIPLE JOB DATES WITH TIME --}}
            <div class="form-section">
                <h5><i class="bi bi-calendar-week"></i>Jadwal Kerja</h5>

                <div id="jobDatesContainer">
                    @php
                        $existingJobDates = $job->jobDatess; // Relasi dari JobDates
                    @endphp

                    @if ($existingJobDates->count() > 0)
                        @foreach ($existingJobDates as $index => $jobDate)
                            <div class="job-date-item" data-index="{{ $index }}">
                                <button type="button" class="remove-date-btn"
                                    onclick="removeJobDate({{ $index }})"
                                    style="{{ $index == 0 && $existingJobDates->count() == 1 ? 'display: none;' : '' }}">
                                    <i class="bi bi-x-lg"></i>
                                </button>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-calendar-day text-primary"></i>
                                            Hari <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select select2-day"
                                            name="job_dates[{{ $index }}][day_id]" required>
                                            <option value="">Pilih Hari</option>
                                            @foreach ($days as $day)
                                                <option value="{{ $day->id }}"
                                                    {{ $jobDate->days_id == $day->id ? 'selected' : '' }}>
                                                    {{ $day->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-calendar-event text-primary"></i>
                                            Tanggal <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control"
                                            name="job_dates[{{ $index }}][date]" value="{{ $jobDate->date }}"
                                            required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-clock text-primary"></i>
                                            Jam Mulai <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="form-control"
                                            name="job_dates[{{ $index }}][start_time]"
                                            value="{{ \Carbon\Carbon::parse($jobDate->start_time)->format('H:i') }}"
                                            required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-clock-fill text-primary"></i>
                                            Jam Selesai <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" class="form-control"
                                            name="job_dates[{{ $index }}][end_time]"
                                            value="{{ \Carbon\Carbon::parse($jobDate->end_time)->format('H:i') }}"
                                            required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
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
                                    <select class="form-select select2-day" name="job_dates[0][day_id]" required>
                                        <option value="">Pilih Hari</option>
                                        @foreach ($days as $day)
                                            <option value="{{ $day->id }}">{{ $day->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-event text-primary"></i>
                                        Tanggal <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="job_dates[0][date]" required>
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
                    @endif
                </div>

                <button type="button" class="btn btn-outline-primary mt-2" onclick="addJobDate()">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Jadwal
                </button>

                <div class="helper-text mt-2">
                    <i class="bi bi-lightbulb"></i>
                    Contoh: Tanggal 22 Desember jam 08:00-10:00, Tanggal 23 Desember jam 09:00-20:00
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
                            value="{{ $job->open_recruitment }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="close_recruitment" class="form-label">
                            <i class="bi bi-calendar-x text-primary"></i>
                            Tanggal Tutup <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="close_recruitment" name="close_recruitment"
                            value="{{ $job->close_recruitment }}" required>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end mb-4 gap-3">
                <a href="{{ route('company.jobs.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-lg me-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary-custom" id="updateBtn">
                    <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let benefitIndex = {{ $job->benefits->count() > 0 ? $job->benefits->count() : 1 }};
    let jobDateIndex = {{ $job->jobDatess->count() > 0 ? $job->jobDatess->count() : 1 }};

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

        $('.select2-day').select2({
            width: '100%',
            placeholder: 'Pilih Hari...',
            allowClear: true
        });

        // Date validation
        $('#open_recruitment').on('change', function() {
            const openDate = $(this).val();
            $('#close_recruitment').attr('min', openDate);
        });

        const openDate = $('#open_recruitment').val();
        if (openDate) {
            $('#close_recruitment').attr('min', openDate);
        }

        // Form submission
        $('#jobEditForm').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Simpan Perubahan?',
                html: 'Apakah Anda yakin ingin menyimpan perubahan lowongan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-check-lg me-1"></i> Ya, Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });
        });

        function submitForm() {
            Swal.fire({
                title: 'Menyimpan Perubahan...',
                html: '<div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            const formData = new FormData($('#jobEditForm')[0]);

            $.ajax({
                url: '{{ route('company.jobs.update', $job->id) }}',
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
                    let errorMessage = 'Gagal menyimpan perubahan';
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
    });

    // ✅ ADD BENEFIT FUNCTION
    // ✅ ADD BENEFIT FUNCTION (UPDATED)
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
                    <select class="form-select select2-benefit benefit-select" name="benefits[${benefitIndex}][benefit_id]">
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
        </div>
    `;

        container.append(newBenefit);

        // Reinitialize Select2 for new benefit
        $(`select[name="benefits[${benefitIndex}][benefit_id]"]`).select2({
            width: '100%',
            placeholder: 'Pilih Benefit...',
            allowClear: true
        });

        // Show remove buttons if more than 1 item
        if ($('.benefit-item').length > 1) {
            $('.benefit-item').find('.remove-benefit-btn').show();
        }

        benefitIndex++;
    }


    // ✅ REMOVE BENEFIT FUNCTION
    function removeBenefit(index) {
        $(`.benefit-item[data-index="${index}"]`).remove();

        if ($('.benefit-item').length === 1) {
            $('.benefit-item').find('.remove-benefit-btn').hide();
        }
    }

    // ✅ ADD JOB DATE FUNCTION
    function addJobDate() {
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
                        <select class="form-select select2-day" name="job_dates[${jobDateIndex}][day_id]" required>
                            <option value="">Pilih Hari</option>
                            @foreach ($days as $day)
                                <option value="{{ $day->id }}">{{ $day->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-event text-primary"></i>
                            Tanggal <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" name="job_dates[${jobDateIndex}][date]" required>
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
            </div>
        `;

        container.append(newJobDate);

        // Reinitialize Select2
        $(`select[name="job_dates[${jobDateIndex}][day_id]"]`).select2({
            width: '100%',
            placeholder: 'Pilih Hari...',
            allowClear: true
        });

        // Show remove buttons
        if ($('.job-date-item').length > 1) {
            $('.job-date-item').find('.remove-date-btn').show();
        }

        jobDateIndex++;
    }

    $('select[name*="[benefit_type]"]').each(function() {
        const $typeSelect = $(this);
        const $amountInput = $typeSelect.closest('.row').find('input[name*="[amount]"]');
        const selectedType = $typeSelect.val();

        console.log('Checking benefit type:', selectedType, 'for input:', $amountInput.attr('name')); // Debug

        if (selectedType === 'in kind') {
            $amountInput.prop('disabled', true);
            $amountInput.attr('placeholder', 'Tidak diperlukan untuk In Kind');
            $amountInput.css('background-color', '#e9ecef');
            console.log('✅ Disabled input for In Kind'); // Debug
        } else if (selectedType === 'cash') {
            $amountInput.prop('disabled', false);
            $amountInput.attr('placeholder', 'Contoh: 500000');
            $amountInput.css('background-color', '#ffffff');
            console.log('✅ Enabled input for Cash'); // Debug
        }
    });
    // ✅ REMOVE JOB DATE FUNCTION
    function removeJobDate(index) {
        $(`.job-date-item[data-index="${index}"]`).remove();

        if ($('.job-date-item').length === 1) {
            $('.job-date-item').find('.remove-date-btn').hide();
        }
    }
    // ✅ HANDLE BENEFIT TYPE CHANGE - DISABLE/ENABLE AMOUNT INPUT
    $(document).on('change', 'select[name*="[benefit_type]"]', function() {
        const $typeSelect = $(this);
        const $amountInput = $typeSelect.closest('.row').find('input[name*="[amount]"]');
        const selectedType = $typeSelect.val();

        console.log('Benefit type changed:', selectedType); // Debug

        if (selectedType === 'in kind') {
            // ✅ Disable & clear amount input untuk In Kind
            $amountInput.prop('disabled', true);
            $amountInput.val('');
            $amountInput.attr('placeholder', 'Tidak diperlukan untuk In Kind');
            $amountInput.css('background-color', '#e9ecef');
        } else if (selectedType === 'cash') {
            // ✅ Enable amount input untuk Cash
            $amountInput.prop('disabled', false);
            $amountInput.attr('placeholder', 'Contoh: 500000');
            $amountInput.css('background-color', '#ffffff');
        } else {
            // ✅ Reset jika belum dipilih
            $amountInput.prop('disabled', false);
            $amountInput.attr('placeholder', 'Contoh: 500000 atau 1 unit');
            $amountInput.css('background-color', '#ffffff');
        }
    });
</script>
