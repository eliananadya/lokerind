@extends('layouts.main')
@section('title', 'My Profile')

@section('content')
    <style>
        .profile-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .profile-sidebar {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .profile-image-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .profile-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .profile-email {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .profile-points {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 1.25rem;
            border-radius: 12px;
            margin-top: 1.25rem;
        }

        .profile-points h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .profile-points .points-value {
            font-size: 2rem;
            font-weight: 800;
            color: #ffd700;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 1.25rem;
            padding-bottom: 0.65rem;
            border-bottom: 3px solid var(--secondary-blue);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 0.2rem rgba(36, 71, 112, 0.15);
        }

        /* ========== BUTTONS ========== */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(20, 72, 155, 0.3);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
        }

        .btn-outline-custom:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
        }

        /* ========== PORTFOLIO SECTION ========== */
        .portfolio-item {
            background: var(--bg-blue);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-blue);
            transition: all 0.3s ease;
            display: flex;
            gap: 1rem;
        }

        .portfolio-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateX(5px);
        }

        .portfolio-preview {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #ddd;
        }

        .portfolio-content {
            flex: 1;
        }

        .portfolio-item strong {
            color: var(--primary-blue);
        }

        .portfolio-item a {
            color: var(--secondary-blue);
            text-decoration: none;
            font-weight: 600;
        }

        /* ========== SELECT2 CUSTOM ========== */
        .select2-container--default .select2-selection--multiple {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            min-height: 42px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--secondary-blue);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--secondary-blue);
            border: none;
            border-radius: 6px;
            padding: 5px 10px;
            color: white;
        }

        /* ========== CARDS ========== */
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .profile-sidebar {
                padding: 1.5rem;
            }

            .profile-image {
                width: 120px;
                height: 120px;
            }

            .section-title {
                font-size: 1.1rem;
            }
        }

        /* ========== INPUT NUMBER STYLING ========== */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 1;
        }

        .no-portfolios-message {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .no-portfolios-message i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>

    <div class="container">
        <div class="profile-container">
            <form id="profileForm">
                @csrf
                <div class="row g-0">
                    {{-- ========== PROFILE SIDEBAR ========== --}}
                    <div class="col-lg-3">
                        <div class="profile-sidebar">
                            <div class="profile-image-wrapper">
                                <img class="profile-image" id="profileImage"
                                    src="{{ $candidate->photo ? Storage::url($candidate->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($candidate->name) . '&size=150&background=14489b&color=fff' }}"
                                    alt="Profile Photo">
                                <div class="profile-image-overlay">
                                    <i class="fas fa-camera fa-2x text-white"></i>
                                </div>
                            </div>
                            <input type="file" id="photoInput" name="profile_photo"
                                accept="image/jpeg,image/jpg,image/png" hidden>

                            <div class="profile-name">{{ $candidate->name }}</div>
                            <div class="profile-email">
                                <i class="fas fa-envelope me-2"></i>{{ $candidate->user->email }}
                            </div>

                            <div class="profile-points">
                                <h4><i class="fas fa-star me-2"></i>Your Points</h4>
                                <div class="points-value">{{ number_format($candidate->point ?? 0) }}</div>
                                <small style="opacity: 0.8;">Reward Points</small>
                            </div>

                            <div class="mt-3">
                                <small class="text-white" style="opacity: 0.8;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Click photo to change
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- ========== MAIN CONTENT ========== --}}
                    <div class="col-lg-9">
                        <div class="p-4">
                            {{-- ========== PERSONAL INFORMATION ========== --}}
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-user-circle"></i>
                                    Personal Information
                                </h3>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $candidate->name }}" placeholder="Enter your name" required>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="number" class="form-control" name="phone_number"
                                            value="{{ $candidate->phone_number }}" placeholder="Enter phone number">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ $candidate->user->email }}" placeholder="Enter email" required>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select" name="gender">
                                            <option value="male"
                                                {{ strtolower($candidate->gender) == 'male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="female"
                                                {{ strtolower($candidate->gender) == 'female' ? 'selected' : '' }}>Female
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" class="form-control" name="birth_date"
                                            value="{{ $candidate->birth_date ? \Carbon\Carbon::parse($candidate->birth_date)->format('Y-m-d') : '' }}">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            Points
                                        </label>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($candidate->point ?? 0) }}" readonly
                                            style="background-color: #f8f9fa; font-weight: 600; color: #14489b;">
                                    </div>

                                    <div class="col-12 form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3" placeholder="Tell us about yourself">{{ $candidate->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- ========== LANGUAGE SKILLS ========== --}}
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-language"></i>
                                    Language Skills
                                </h3>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">English Level</label>
                                        <select name="level_english" class="form-select">
                                            <option value="beginner"
                                                {{ $candidate->level_english == 'beginner' ? 'selected' : '' }}>Beginner
                                            </option>
                                            <option value="intermediate"
                                                {{ $candidate->level_english == 'intermediate' ? 'selected' : '' }}>
                                                Intermediate</option>
                                            <option value="expert"
                                                {{ $candidate->level_english == 'expert' ? 'selected' : '' }}>Expert
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Mandarin Level</label>
                                        <select name="level_mandarin" class="form-select">
                                            <option value="beginner"
                                                {{ $candidate->level_mandarin == 'beginner' ? 'selected' : '' }}>Beginner
                                            </option>
                                            <option value="intermediate"
                                                {{ $candidate->level_mandarin == 'intermediate' ? 'selected' : '' }}>
                                                Intermediate</option>
                                            <option value="expert"
                                                {{ $candidate->level_mandarin == 'expert' ? 'selected' : '' }}>Expert
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- ========== PREFERENCES ========== --}}
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-sliders-h"></i>
                                    Job Preferences
                                </h3>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Minimum Salary Expectation</label>
                                        <input type="number" class="form-control" name="min_salary"
                                            value="{{ $candidate->min_salary ?? '' }}" placeholder="Enter minimum salary"
                                            min="0" step="100000">
                                        <small class="text-muted">In IDR (Rupiah)</small>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Preferred Cities</label>
                                        <select class="form-select select_kota" name="preferred_cities[]" multiple>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ in_array($city->id, $candidate->preferredCities->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Preferred Days</label>
                                        <select class="form-select select_days" name="preferred_days[]" multiple>
                                            @foreach ($days as $day)
                                                <option value="{{ $day->id }}"
                                                    {{ in_array($day->id, $candidate->days->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $day->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Preferred Industries</label>
                                        <select class="form-select select_industries" name="preferred_industries[]"
                                            multiple>
                                            @foreach ($industries as $industry)
                                                <option value="{{ $industry->id }}"
                                                    {{ in_array($industry->id, $candidate->preferredIndustries->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $industry->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Preferred Job Types</label>
                                        <select class="form-select select_jobs" name="preferred_jobs[]" multiple>
                                            @foreach ($typeJobs as $job)
                                                <option value="{{ $job->id }}"
                                                    {{ in_array($job->id, $candidate->preferredTypeJobs->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $job->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Skills</label>
                                        <select class="form-select select_skills" name="preferred_skills[]" multiple>
                                            @foreach ($skills as $skill)
                                                <option value="{{ $skill->id }}"
                                                    {{ in_array($skill->id, $candidate->skills->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $skill->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- ========== PORTFOLIO ========== --}}
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-briefcase"></i>
                                    Portfolio
                                </h3>

                                <div id="portfolioList" class="mb-4">
                                    @forelse ($candidate->portofolios as $portfolio)
                                        <div class="portfolio-item" data-portfolio-id="{{ $portfolio->id }}">
                                            @php
                                                $fileUrl = Storage::url($portfolio->file);
                                                $extension = pathinfo($portfolio->file, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), [
                                                    'jpg',
                                                    'jpeg',
                                                    'png',
                                                    'gif',
                                                    'webp',
                                                ]);
                                            @endphp

                                            @if ($isImage)
                                                <img src="{{ $fileUrl }}" alt="Portfolio"
                                                    class="portfolio-preview">
                                            @else
                                                <div
                                                    class="portfolio-preview d-flex align-items-center justify-content-center bg-light">
                                                    <i
                                                        class="fas fa-file-{{ strtolower($extension) == 'pdf' ? 'pdf' : 'alt' }} fa-2x text-secondary"></i>
                                                </div>
                                            @endif

                                            <div class="portfolio-content">
                                                <p class="mb-1"><strong>Caption:</strong> {{ $portfolio->caption }}</p>
                                                <p class="mb-2">
                                                    <strong>File:</strong>
                                                    <a href="{{ $fileUrl }}" target="_blank">
                                                        <i class="fas fa-external-link-alt me-1"></i>View File
                                                    </a>
                                                </p>
                                                <button class="btn btn-danger btn-sm delete-portfolio-btn"
                                                    data-candidate-id="{{ $candidate->id }}"
                                                    data-portfolio-id="{{ $portfolio->id }}" type="button">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="no-portfolios-message" id="noPortfolioMessage">
                                            <i class="fas fa-folder-open d-block"></i>
                                            <p class="text-muted">No portfolios added yet</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="border-top pt-3">
                                    <h5 class="mb-3"><i class="fas fa-plus-circle me-2"></i>Add New Portfolio</h5>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Upload File</label>
                                            <input type="file" class="form-control" id="portfolio_file"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            <small class="text-muted">PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Caption</label>
                                            <input type="text" class="form-control" id="portfolio_caption"
                                                placeholder="Enter caption">
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-outline-custom" type="button" id="addPortfolioBtn">
                                                <i class="fas fa-upload me-2"></i>Upload Portfolio
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ========== SAVE BUTTON ========== --}}
                            <div class="text-center mt-4">
                                <button class="btn btn-primary-custom btn-lg px-5" type="submit" id="simpanProfile">
                                    <i class="fas fa-save me-2"></i>Save All Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== SCRIPTS ========== --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // ===== INITIALIZE SELECT2 =====
            $('.select_kota, .select_days, .select_industries, .select_jobs, .select_skills').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });

            // ===== PHOTO UPLOAD HANDLER =====
            $('#profileImage').on('click', function() {
                $('#photoInput').click();
            });

            $('#photoInput').on('change', function(e) {
                var file = e.target.files[0];
                if (!file) return;

                var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload JPG, JPEG, or PNG image only.',
                    });
                    $(this).val('');
                    return;
                }

                var maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Image size must not exceed 2MB.',
                    });
                    $(this).val('');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profileImage').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                Swal.fire({
                    icon: 'info',
                    title: 'Photo Selected',
                    text: 'Click "Save All Changes" to upload the new photo.',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            // ===== SAVE PROFILE HANDLER =====
            $('#simpanProfile').on('click', function(e) {
                e.preventDefault();

                var formData = new FormData();
                formData.append('name', $('input[name="name"]').val());
                formData.append('phone_number', $('input[name="phone_number"]').val());
                formData.append('email', $('input[name="email"]').val());
                formData.append('gender', $('select[name="gender"]').val());
                formData.append('description', $('textarea[name="description"]').val());
                formData.append('birth_date', $('input[name="birth_date"]').val());
                formData.append('level_english', $('select[name="level_english"]').val());
                formData.append('level_mandarin', $('select[name="level_mandarin"]').val());
                formData.append('min_salary', $('input[name="min_salary"]').val());
                formData.append('_token', '{{ csrf_token() }}');

                // Profile photo
                var photoInput = $('#photoInput')[0];
                if (photoInput && photoInput.files.length > 0) {
                    formData.append('profile_photo', photoInput.files[0]);
                }

                // Skills
                var preferredSkills = $('.select_skills').val();
                if (preferredSkills && preferredSkills.length > 0) {
                    preferredSkills.forEach(function(skill) {
                        formData.append('preferred_skills[]', skill);
                    });
                }

                // Cities
                var preferredCities = $('.select_kota').val();
                if (preferredCities && preferredCities.length > 0) {
                    preferredCities.forEach(function(city) {
                        formData.append('preferred_cities[]', city);
                    });
                }

                // Days
                var preferredDays = $('.select_days').val();
                if (preferredDays && preferredDays.length > 0) {
                    preferredDays.forEach(function(day) {
                        formData.append('preferred_days[]', day);
                    });
                }

                // Industries
                var preferredIndustries = $('.select_industries').val();
                if (preferredIndustries && preferredIndustries.length > 0) {
                    preferredIndustries.forEach(function(industry) {
                        formData.append('preferred_industries[]', industry);
                    });
                }

                // Type Jobs
                var preferredJobs = $('.select_jobs').val();
                if (preferredJobs && preferredJobs.length > 0) {
                    preferredJobs.forEach(function(job) {
                        formData.append('preferred_type_jobs[]', job);
                    });
                }

                $.ajax({
                    url: '{{ route('candidate.updateProfile') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#simpanProfile').prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm me-2"></span>Saving...'
                        );
                    },
                    success: function(response) {
                        $('#photoInput').val('');

                        if (response.photo_url) {
                            $('#profileImage').attr('src', response.photo_url);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated!',
                            text: 'Your profile has been successfully updated.',
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        $('#simpanProfile').prop('disabled', false).html(
                            '<i class="fas fa-save me-2"></i>Save All Changes'
                        );

                        var errorMessage = 'An error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                errorMessage += messages.join('<br>') + '<br>';
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            html: errorMessage,
                        });
                    }
                });
            });

            // ===== ADD PORTFOLIO HANDLER =====
            $('#addPortfolioBtn').on('click', function(e) {
                e.preventDefault();

                var fileInput = $('#portfolio_file')[0];
                var caption = $('#portfolio_caption').val().trim();

                if (caption === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Caption Required',
                        text: 'Please enter a caption for the portfolio.',
                    });
                    return;
                }

                if (fileInput.files.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No File Selected',
                        text: 'Please select a file to upload.',
                    });
                    return;
                }

                var file = fileInput.files[0];
                var maxSize = 5 * 1024 * 1024;

                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'File size must not exceed 5MB.',
                    });
                    return;
                }

                var allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                var fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload PDF, DOC, DOCX, JPG, or PNG files only.',
                    });
                    return;
                }

                var formData = new FormData();
                formData.append('file', file);
                formData.append('caption', caption);
                formData.append('_token', '{{ csrf_token() }}');

                var url = '{{ route('candidate.add.portfolio', ['candidate' => $candidate->id]) }}';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#addPortfolioBtn').prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...'
                        );
                    },
                    success: function(response) {
                        $('#portfolio_file').val('');
                        $('#portfolio_caption').val('');
                        $('#addPortfolioBtn').prop('disabled', false).html(
                            '<i class="fas fa-upload me-2"></i>Upload Portfolio'
                        );

                        $('#noPortfolioMessage').remove();

                        if (response.success && response.portfolio) {
                            var fileUrl = response.portfolio.file_url;
                            var extension = fileUrl.split('.').pop().toLowerCase();
                            var isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(
                                extension);

                            var previewHtml = isImage ?
                                '<img src="' + fileUrl +
                                '" alt="Portfolio" class="portfolio-preview">' :
                                '<div class="portfolio-preview d-flex align-items-center justify-content-center bg-light"><i class="fas fa-file-' +
                                (extension === 'pdf' ? 'pdf' : 'alt') +
                                ' fa-2x text-secondary"></i></div>';

                            var newPortfolio = `
                                <div class="portfolio-item" data-portfolio-id="${response.portfolio.id}">
                                    ${previewHtml}
                                    <div class="portfolio-content">
                                        <p class="mb-1"><strong>Caption:</strong> ${response.portfolio.caption}</p>
                                        <p class="mb-2">
                                            <strong>File:</strong>
                                            <a href="${fileUrl}" target="_blank">
                                                <i class="fas fa-external-link-alt me-1"></i>View File
                                            </a>
                                        </p>
                                        <button class="btn btn-danger btn-sm delete-portfolio-btn"
                                                data-candidate-id="{{ $candidate->id }}"
                                                data-portfolio-id="${response.portfolio.id}"
                                                type="button">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            `;

                            $('#portfolioList').prepend(newPortfolio);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Portfolio Added!',
                            text: 'Your portfolio has been successfully uploaded.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        $('#addPortfolioBtn').prop('disabled', false).html(
                            '<i class="fas fa-upload me-2"></i>Upload Portfolio'
                        );

                        var errorMessage = 'Failed to upload portfolio.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = '<ul class="text-start mb-0">';
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                $.each(messages, function(index, message) {
                                    errorMessage += '<li>' + message + '</li>';
                                });
                            });
                            errorMessage += '</ul>';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            html: errorMessage,
                        });
                    }
                });
            });

            // ===== DELETE PORTFOLIO HANDLER =====
            $(document).on('click', '.delete-portfolio-btn', function(e) {
                e.preventDefault();

                var button = $(this);
                var candidateId = button.data('candidate-id');
                var portfolioId = button.data('portfolio-id');
                var portfolioItem = button.closest('.portfolio-item');

                var url =
                    '{{ route('candidate.delete.portfolio', ['candidate' => ':candidate', 'portfolio' => ':portfolio']) }}';
                url = url.replace(':candidate', candidateId).replace(':portfolio', portfolioId);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                button.prop('disabled', true).html(
                                    '<span class="spinner-border spinner-border-sm"></span>'
                                );
                            },
                            success: function(response) {
                                portfolioItem.fadeOut(300, function() {
                                    $(this).remove();

                                    if ($('.portfolio-item').length === 0) {
                                        $('#portfolioList').html(`
                                            <div class="no-portfolios-message" id="noPortfolioMessage">
                                                <i class="fas fa-folder-open d-block"></i>
                                                <p class="text-muted">No portfolios added yet</p>
                                            </div>
                                        `);
                                    }
                                });

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Portfolio has been successfully deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                button.prop('disabled', false).html(
                                    '<i class="fas fa-trash me-1"></i>Delete'
                                );

                                var errorMessage = 'Failed to delete portfolio.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: errorMessage,
                                });
                            }
                        });
                    }
                });
            });

            // ===== DISPLAY SESSION MESSAGES =====
            @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#14489b'
                });
            @elseif (session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>
@endsection
