@extends('layouts.main')

@section('title', 'Company Profile')

<style>
    .profile-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ===== HEADER SECTION ===== */
    .profile-header {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        padding: 2rem;
        color: white;
        text-align: center;
    }

    .company-name {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .company-email {
        opacity: 0.9;
        font-size: 1rem;
    }

    /* ===== FORM SECTION ===== */
    .form-section {
        padding: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-blue);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--light-blue);
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 0.6rem 0.875rem;
        transition: border-color 0.2s;
        font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(20, 72, 155, 0.1);
    }

    .required-mark {
        color: #ef4444;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* ===== STATISTICS SECTION ===== */
    .stats-section {
        background: #f9fafb;
        padding: 2rem;
        border-left: 1px solid #e5e7eb;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        transition: box-shadow 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-primary .stat-icon {
        background: var(--bg-blue);
        color: var(--primary-blue);
    }

    .stat-primary .stat-value {
        color: var(--primary-blue);
    }

    .stat-success .stat-icon {
        background: #d1fae5;
        color: #059669;
    }

    .stat-success .stat-value {
        color: #059669;
    }

    .stat-info .stat-icon {
        background: #cffafe;
        color: #0891b2;
    }

    .stat-info .stat-value {
        color: #0891b2;
    }

    .stat-warning .stat-icon {
        background: #fef3c7;
        color: #d97706;
    }

    .stat-warning .stat-value {
        color: #d97706;
    }

    .stat-secondary .stat-icon {
        background: #ede9fe;
        color: #7c3aed;
    }

    .stat-secondary .stat-value {
        color: #7c3aed;
    }

    /* ===== MEMBER CARD ===== */
    .member-card {
        background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
        color: white;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        margin-top: 1rem;
    }

    .member-card i {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        opacity: 0.9;
    }

    .member-card h6 {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .member-card p {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    /* ===== RATING DISPLAY ===== */
    .rating-display {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        border-radius: 6px;
        padding: 0.6rem 0.875rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .rating-display input {
        border: none;
        background: transparent;
        font-size: 1.25rem;
        font-weight: 700;
        color: #92400e;
        width: 60px;
        text-align: left;
        padding: 0;
    }

    .rating-display .input-group-text {
        background: transparent;
        border: none;
        font-size: 1.25rem;
        padding: 0;
    }

    /* ===== BUTTONS ===== */
    .btn-save {
        background: var(--primary-blue);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-save:hover {
        background: var(--dark-blue);
        transform: translateY(-1px);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991px) {
        .stats-section {
            border-left: none;
            border-top: 1px solid #e5e7eb;
        }
    }
</style>

@section('content')
    <div class="container py-4">
        <div class="profile-container">
            <form id="profileForm">
                @csrf

                <!-- Header -->
                <div class="profile-header">
                    <div class="company-name">{{ $company->name }}</div>
                    <div class="company-email">{{ $user->email }}</div>
                </div>

                <div class="row g-0">
                    <!-- Form Section -->
                    <div class="col-lg-7">
                        <div class="form-section">
                            <h5 class="section-title">Profile Information</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Company Name <span class="required-mark">*</span>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter company name"
                                        name="company_name" value="{{ $company->name }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Phone Number <span class="required-mark">*</span>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter phone number"
                                        name="phone_number" value="{{ $company->phone_number }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Email Address <span class="required-mark">*</span>
                                    </label>
                                    <input type="email" class="form-control" placeholder="Enter email" name="email"
                                        value="{{ $user->email }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Location <span class="required-mark">*</span>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter location" name="location"
                                        value="{{ $company->location }}" required>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">
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

                                <div class="col-12 mb-3">
                                    <label class="form-label">Company Description</label>
                                    <textarea class="form-control" placeholder="Tell us about your company..." name="description" rows="3">{{ old('description', $company->description) }}</textarea>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Average Rating</label>
                                    <div class="rating-display">
                                        <input type="text" class="form-control"
                                            value="{{ number_format($company->avg_rating, 1) }}" readonly>
                                        <span class="input-group-text">
                                            <i class="bi bi-star-fill text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn-save" type="submit">
                                    <i class="bi bi-check-circle me-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Section -->
                    <div class="col-lg-5">
                        <div class="stats-section">
                            <h5 class="section-title">Statistics Overview</h5>

                            <div class="stat-card stat-secondary">
                                <div class="stat-header">
                                    <div class="stat-icon">
                                        <i class="bi bi-bell-fill"></i>
                                    </div>
                                    <div class="stat-label">Subscribers</div>
                                </div>
                                <div class="stat-value">{{ $company->subscribes->count() }}</div>
                            </div>

                            <div class="member-card">
                                <h6>Member Since</h6>
                                <p>{{ $company->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Form Submit
            $('#profileForm').submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);

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
                                title: 'Success!',
                                text: response.message ||
                                    'Profile updated successfully',
                                confirmButtonColor: '#14489b',
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to update profile';
                        let errorList = '';

                        if (xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorList = '<ul class="text-start mb-0">';
                            Object.values(errors).forEach(function(error) {
                                errorList += `<li>${error[0]}</li>`;
                            });
                            errorList += '</ul>';
                        } else if (xhr.responseJSON?.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            html: errorList || errorMessage,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });
        });
    </script>
@endsection
