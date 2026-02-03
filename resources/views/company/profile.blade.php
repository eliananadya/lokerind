@extends('layouts.main')

@section('title', 'Company Profile')

<style>
    .profile-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    /* ===== PHOTO SECTION ===== */
    .photo-section {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        padding: 2rem;
        color: white;
    }

    .profile-photo-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .profile-photo-container:hover #photoOverlay {
        opacity: 1 !important;
    }

    .profile-photo-container:hover #profileImage {
        filter: brightness(0.7);
        transform: scale(1.05);
    }

    #profileImage {
        transition: all 0.3s ease;
        border: 5px solid white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    #photoOverlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 150px;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .company-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }

    .company-email {
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .photo-hint {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-top: 1rem;
        display: inline-block;
    }

    /* ===== FORM SECTION ===== */
    .form-section {
        padding: 2.5rem;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid var(--light-blue);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
        font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(20, 72, 155, 0.1);
    }

    .required-mark {
        color: #ef4444;
        font-weight: 700;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    /* ===== STATISTICS SECTION ===== */
    .stats-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0;
    }

    .stat-primary .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .stat-primary .stat-value {
        color: #3b82f6;
    }

    .stat-success .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .stat-success .stat-value {
        color: #10b981;
    }

    .stat-info .stat-icon {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .stat-info .stat-value {
        color: #06b6d4;
    }

    .stat-warning .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .stat-warning .stat-value {
        color: #f59e0b;
    }

    .stat-secondary .stat-icon {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .stat-secondary .stat-value {
        color: #8b5cf6;
    }

    /* ===== BUTTONS ===== */
    .btn-save-profile {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 1rem 3rem;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-save-profile:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669, #047857);
    }

    .btn-save-profile:active {
        transform: translateY(-1px);
    }

    /* ===== RATING DISPLAY ===== */
    .rating-display {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 2px solid #fbbf24;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .rating-display input {
        border: none;
        background: transparent;
        font-size: 1.5rem;
        font-weight: 700;
        color: #92400e;
        text-align: center;
    }

    .rating-display .input-group-text {
        background: transparent;
        border: none;
        font-size: 1.5rem;
    }

    /* ===== PASSWORD SECTION ===== */
    .password-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px dashed #d1d5db;
        margin-top: 2rem;
    }

    .password-section-title {
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ===== MEMBER SINCE CARD ===== */
    .member-card {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        color: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        margin-top: 2rem;
    }

    .member-card i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .member-card h6 {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .member-card p {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .stat-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .stat-card:nth-child(5) {
        animation-delay: 0.5s;
    }
</style>

@section('content')
    <div class="container mt-5 mb-5">
        <div class="profile-container">
            <form id="profileForm">
                @csrf
                <div class="row g-0">
                    {{-- LEFT SIDEBAR - PHOTO --}}
                    <div class="col-lg-3">
                        <div class="photo-section text-center">
                            <div class="profile-photo-container">
                                <img class="rounded-circle" width="150" height="150" id="profileImage"
                                    src="{{ $user->photo ? Storage::url($user->photo) : 'https://ui-avatars.com/api/? name=' . urlencode($company->name) . '&size=150&background=random' }}"
                                    style="cursor: pointer; object-fit: cover;">

                                <div id="photoOverlay">
                                    <i class="bi bi-camera-fill"></i>
                                </div>
                            </div>

                            <input type="file" id="photoInput" name="profile_photo"
                                accept="image/jpeg,image/jpg,image/png" style="display: none;">

                            <div class="company-name">{{ $company->name }}</div>
                            <div class="company-email">{{ $user->email }}</div>
                            <div class="photo-hint">
                                <i class="bi bi-info-circle me-1"></i>
                                Click photo to change
                            </div>
                        </div>
                    </div>

                    {{-- MIDDLE SECTION - FORM --}}
                    <div class="col-lg-5">
                        <div class="form-section">
                            <h4 class="section-title">
                                <i class="bi bi-gear-fill"></i>
                                Profile Settings
                            </h4>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-building"></i>
                                    Company Name <span class="required-mark">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter company name"
                                    name="company_name" value="{{ $company->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-telephone"></i>
                                    Phone Number <span class="required-mark">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter phone number"
                                    name="phone_number" value="{{ $company->phone_number }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email Address <span class="required-mark">*</span>
                                </label>
                                <input type="email" class="form-control" placeholder="Enter email" name="email"
                                    value="{{ $user->email }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-geo-alt"></i>
                                    Location <span class="required-mark">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter company location"
                                    name="location" value="{{ $company->location }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-briefcase"></i>
                                    Industry <span class="required-mark">*</span>
                                </label>
                                <select class="form-select" name="industries_id" required>
                                    <option value="">Select Industry</option>
                                    @foreach ($industries as $industry)
                                        <option value="{{ $industry->id }}"
                                            {{ $company->industries_id == $industry->id ? 'selected' : '' }}>
                                            {{ $industry->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-file-text"></i>
                                    Company Description
                                </label>
                                <textarea class="form-control" placeholder="Tell us about your company..." name="description" rows="4">{{ old('description', $company->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-star-fill"></i>
                                    Average Rating
                                </label>
                                <div class="rating-display">
                                    <input type="text" class="form-control"
                                        value="{{ number_format($company->avg_rating, 1) }}" readonly>
                                    <span class="input-group-text">
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="password-section">
                                <h6 class="password-section-title">
                                    <i class="bi bi-shield-lock"></i>
                                    Change Password
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-key"></i>
                                        New Password
                                    </label>
                                    <input type="password" class="form-control" placeholder="Enter new password"
                                        name="new_password" id="new_password" minlength="8">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Leave blank if you don't want to change
                                    </small>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">
                                        <i class="bi bi-key"></i>
                                        Confirm Password
                                    </label>
                                    <input type="password" class="form-control" placeholder="Confirm new password"
                                        name="new_password_confirmation" id="new_password_confirmation" minlength="8">
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button class="btn-save-profile" type="submit" id="saveProfileBtn">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SECTION - STATISTICS --}}
                    <div class="col-lg-4">
                        <div class="stats-section">
                            <h5 class="section-title" style="font-size: 1.5rem;">
                                <i class="bi bi-bar-chart-fill"></i>
                                Statistics
                            </h5>

                            <div class="stat-card stat-primary">
                                <div class="stat-icon">
                                    <i class="bi bi-briefcase-fill"></i>
                                </div>
                                <div class="stat-label">Total Job Postings</div>
                                <div class="stat-value">{{ $company->jobPostings->count() }}</div>
                            </div>

                            <div class="stat-card stat-success">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="stat-label">Active Jobs</div>
                                <div class="stat-value">{{ $company->jobPostings->where('status', 'Open')->count() }}
                                </div>
                            </div>

                            <div class="stat-card stat-info">
                                <div class="stat-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="stat-label">Total Applicants</div>
                                <div class="stat-value">{{ $company->applications->count() }}</div>
                            </div>

                            <div class="stat-card stat-warning">
                                <div class="stat-icon">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div class="stat-label">Total Reviews</div>
                                <div class="stat-value">{{ $company->reviews->count() }}</div>
                            </div>

                            <div class="stat-card stat-secondary">
                                <div class="stat-icon">
                                    <i class="bi bi-bell-fill"></i>
                                </div>
                                <div class="stat-label">Subscribers</div>
                                <div class="stat-value">{{ $company->subscribes->count() }}</div>
                            </div>

                            <div class="member-card">
                                <i class="bi bi-calendar-check-fill"></i>
                                <h6>Member Since</h6>
                                <p>{{ $company->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // ===== PHOTO UPLOAD WITH CONFIRMATION =====
            $('#profileImage').click(function() {
                $('#photoInput').click();
            });

            $('#photoInput').change(function() {
                const file = this.files[0];

                if (file) {
                    // Validate file size (max 2MB)
                    if (file.size > 2048000) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Terlalu Besar',
                            text: 'Ukuran maksimal file adalah 2MB',
                            confirmButtonColor: '#dc3545'
                        });
                        $(this).val('');
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tipe File Tidak Valid',
                            text: 'Hanya file JPEG, JPG, dan PNG yang diperbolehkan',
                            confirmButtonColor: '#dc3545'
                        });
                        $(this).val('');
                        return;
                    }

                    // Show confirmation
                    Swal.fire({
                        title: 'Update Foto Profil? ',
                        html: `
                            <div class="text-start">
                                <p class="mb-2">Apakah Anda yakin ingin mengubah foto profil perusahaan? </p>
                                <div class="alert alert-info py-2 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small><strong>File:</strong> ${file.name}</small><br>
                                    <small><strong>Size:</strong> ${(file.size / 1024).toFixed(2)} KB</small>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-upload me-1"></i> Ya, Upload',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            uploadPhoto(file);
                        } else {
                            $('#photoInput').val('');
                        }
                    });
                }
            });

            function uploadPhoto(file) {
                Swal.fire({
                    title: 'Mengupload Foto...',
                    html: '<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData();
                formData.append('profile_photo', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route('company.profile.uploadPhoto') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#profileImage').attr('src', response.photo_url);
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Gagal',
                            text: xhr.responseJSON?.message || 'Gagal mengupload foto profil',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }

            // ===== FORM SUBMIT =====
            $('#profileForm').submit(function(e) {
                e.preventDefault();

                const newPassword = $('#new_password').val();
                const confirmPassword = $('#new_password_confirmation').val();

                if (newPassword && newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Konfirmasi password tidak sesuai dengan password baru',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }

                const companyName = $('input[name="company_name"]').val();
                const phoneNumber = $('input[name="phone_number"]').val();
                const email = $('input[name="email"]').val();
                const location = $('input[name="location"]').val();
                const industryText = $('select[name="industries_id"] option:selected').text();

                Swal.fire({
                    title: 'Update Profil Perusahaan?',
                    html: `
                        <div class="text-start">
                            <p class="mb-3">Apakah Anda yakin ingin menyimpan perubahan profil? </p>
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-3">Data yang akan diupdate:</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" width="40%"><i class="bi bi-building me-1"></i> Nama Perusahaan:</td>
                                            <td class="fw-bold">${companyName}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-telephone me-1"></i> Telepon:</td>
                                            <td class="fw-bold">${phoneNumber}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-envelope me-1"></i> Email:</td>
                                            <td class="fw-bold">${email}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-geo-alt me-1"></i> Lokasi:</td>
                                            <td class="fw-bold">${location}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-briefcase me-1"></i> Industri:</td>
                                            <td class="fw-bold">${industryText}</td>
                                        </tr>
                                        ${newPassword ? '<tr><td class="text-muted"><i class="bi bi-lock me-1"></i> Password:</td><td class="text-success">✓ Akan diubah</td></tr>' : ''}
                                    </table>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-save me-1"></i> Ya, Simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    width: '600px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitProfileUpdate();
                    }
                });
            });

            function submitProfileUpdate() {
                Swal.fire({
                    title: 'Menyimpan Perubahan...',
                    html: '<div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData($('#profileForm')[0]);

                $.ajax({
                    url: '{{ route('company.profile.update') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Diperbarui! ',
                                html: `
                                    <p>${response.message}</p>
                                    <small class="text-muted">Halaman akan dimuat ulang dalam 2 detik...</small>
                                `,
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal memperbarui profil';
                        let errorDetails = '';

                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = 'Validasi Gagal';
                            errorDetails = '<ul class="text-start">';
                            Object.values(errors).forEach(function(error) {
                                errorDetails += `<li>${error[0]}</li>`;
                            });
                            errorDetails += '</ul>';
                        } else if (xhr.responseJSON?.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: errorMessage,
                            html: errorDetails || 'Terjadi kesalahan saat memperbarui profil',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    </script>
@endsection
