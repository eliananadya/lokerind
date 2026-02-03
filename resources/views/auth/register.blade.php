@php
    $hideNavbar = true;
    $hideFooter = true;
@endphp
@extends('layouts.main')

@section('content')
    <div class="login-container">
        <img src="{{ asset('assets/images/login.png') }}" alt="Job Illustration" class="full-image">
        <div class="image-overlay"></div>
        <div class="left-content">
            <div>
                <a href="{{ url('/') }}" class="back-button">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>

            <div class="hero-text">
                <h1>Mulai Perjalanan<br>Karir Anda Sekarang</h1>
                <p>Daftar sekarang dan akses ribuan peluang kerja dari<br>perusahaan-perusahaan terpercaya di seluruh
                    Indonesia.</p>
            </div>
        </div>

        <div class="form-overlay">
            <div class="login-box">
                <form id="register-form" enctype="multipart/form-data">
                    @csrf
                    <!-- STEP 1: Basic Info -->
                    <div id="step1" class="step-content active">
                        <div class="mb-4">
                            <h2 class="fw-bold mb-2">Create Account</h2>
                            <p class="text-muted">Join for free</p>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name"
                                placeholder="Input your name" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email"
                                placeholder="Input your email" required>
                            <div id="email-error" class="invalid-feedback d-block" style="display: none !important;"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control form-control-lg" id="password" name="password"
                                    placeholder="Input your password" minlength="8" required>
                                <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control form-control-lg" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm your password" required>
                                <i class="bi bi-eye-slash password-toggle" id="togglePasswordConfirm"></i>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="button" class="btn btn-primary-custom btn-lg text-white" data-next="step2">
                                Next
                            </button>
                        </div>

                        <div class="text-center">
                            <span class="text-muted">Already have an account?</span>
                            <a href="{{ route('login') }}" class="text-decoration-none text-primary-custom fw-semibold">
                                Sign in here
                            </a>
                        </div>
                    </div>

                    <!-- STEP 2: Choose Role -->
                    <div id="step2" class="step-content">
                        <div class="mb-4">
                            <h2 class="fw-bold mb-2">Choose Your Role</h2>
                            <p class="text-muted">Select how you want to use our platform</p>
                        </div>

                        <div class="mb-4">
                            <div class="role-card mb-3">
                                <input type="radio" name="role" id="role-user" value="user" class="d-none">
                                <label for="role-user" class="w-100" style="cursor: pointer;">
                                    <div class="card role-option">
                                        <div class="card-body text-center py-4">
                                            <i class="bi bi-person-circle fs-1 text-primary-custom mb-2"></i>
                                            <h5 class="fw-semibold mb-1">I'm a Candidate</h5>
                                            <p class="text-muted small mb-0">Looking for job opportunities</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="role-card">
                                <input type="radio" name="role" id="role-company" value="company" class="d-none">
                                <label for="role-company" class="w-100" style="cursor: pointer;">
                                    <div class="card role-option">
                                        <div class="card-body text-center py-4">
                                            <i class="bi bi-bank fs-1 text-primary-custom mb-2"></i>
                                            <h5 class="fw-semibold mb-1">I'm a Company</h5>
                                            <p class="text-muted small mb-0">Looking for talented candidates</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-2">
                            <button type="button" class="btn btn-primary-custom btn-lg text-white" data-next="step3">
                                Continue
                            </button>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-secondary btn-lg" data-prev="step1">
                                Back
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Candidate Basic Info -->
                    <div id="step3-candidate" class="step-content">
                        <div class="mb-4">
                            <h2 class="fw-bold mb-2">Basic Information</h2>
                            <p class="text-muted">Complete your basic profile</p>
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label fw-semibold">Phone Number <span
                                    class="text-danger">*</span></label>
                            <input type="tel" class="form-control form-control-lg" id="phone_number"
                                name="phone_number" placeholder="Input your phone number" pattern="[0-9]+" required>
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label fw-semibold">Gender <span
                                    class="text-danger">*</span></label>
                            <select id="gender" name="gender" class="form-select form-select-lg" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="birth_date" class="form-label fw-semibold">Birth Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-lg" id="birth_date" name="birth_date"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Tell us about yourself"></textarea>
                        </div>

                        <div class="d-grid mb-2">
                            <button type="button" class="btn btn-primary-custom btn-lg text-white"
                                data-next="step4-candidate">
                                Continue
                            </button>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-secondary btn-lg" data-prev="step2">
                                Back
                            </button>
                        </div>
                    </div>

                    <!-- STEP 4: Candidate Additional Info -->
                    <div id="step4-candidate" class="step-content">
                        <div class="mb-4">
                            <h2 class="fw-bold mb-2">Additional Information</h2>
                            <p class="text-muted">These fields are optional</p>
                        </div>

                        <div class="mb-3">
                            <label for="level_mandarin" class="form-label fw-semibold">Mandarin Level (Optional)</label>
                            <select id="level_mandarin" name="level_mandarin" class="form-select form-select-lg">
                                <option value="">Select Mandarin Level</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="level_english" class="form-label fw-semibold">English Level (Optional)</label>
                            <select id="level_english" name="level_english" class="form-select form-select-lg">
                                <option value="">Select English Level</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="min_height" class="form-label fw-semibold">Height (cm) (Optional)</label>
                                <input type="number" class="form-control form-control-lg" id="min_height"
                                    name="min_height" placeholder="170">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="min_weight" class="form-label fw-semibold">Weight (kg) (Optional)</label>
                                <input type="number" class="form-control form-control-lg" id="min_weight"
                                    name="min_weight" placeholder="65">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="min_salary" class="form-label fw-semibold">Expected Salary (IDR)
                                (Optional)</label>
                            <input type="number" class="form-control form-control-lg" id="min_salary" name="min_salary"
                                placeholder="5000000">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferred Working Days (Optional)</label>
                            <select id="preferred_days" name="preferred_days[]"
                                class="form-select form-select-lg select2-multiple" multiple="multiple">
                                @foreach ($days as $day)
                                    <option value="{{ $day->id }}">{{ $day->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferred Cities (Optional)</label>
                            <select id="preferred_cities" name="preferred_cities[]"
                                class="form-select form-select-lg select2-multiple" multiple="multiple">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Your Skills (Optional)</label>
                            <select id="preferred_skills" name="preferred_skills[]"
                                class="form-select form-select-lg select2-multiple" multiple="multiple">
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferred Job Types (Optional)</label>
                            <select id="preferred_jobs" name="preferred_jobs[]"
                                class="form-select form-select-lg select2-multiple" multiple="multiple">
                                @foreach ($typeJobs as $typeJob)
                                    <option value="{{ $typeJob->id }}">{{ $typeJob->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferred Industries (Optional)</label>
                            <select id="preferred_industries" name="preferred_industries[]"
                                class="form-select form-select-lg select2-multiple" multiple="multiple">
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="portfolios" class="form-label fw-semibold">Portfolio Files (Optional)</label>
                            <input type="file" class="form-control form-control-lg" id="portfolios"
                                name="portfolios[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Max 5MB each</small>
                            <div id="portfolio-preview" class="mt-3"></div>
                        </div>

                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-primary-custom btn-lg text-white">
                                Create Account
                            </button>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-secondary btn-lg" data-prev="step3-candidate">
                                Back
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Company Fields -->
                    <div id="step3-company" class="step-content">
                        <div class="mb-4">
                            <h2 class="fw-bold mb-2">Company Information</h2>
                            <p class="text-muted">Tell us about your company</p>
                        </div>

                        <div class="mb-3">
                            <label for="company_name" class="form-label fw-semibold">
                                Company Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="company_name"
                                name="company_name" placeholder="Input company name" required>
                        </div>

                        <div class="mb-3">
                            <label for="company_phone_number" class="form-label fw-semibold">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control form-control-lg" id="company_phone_number"
                                name="company_phone_number" placeholder="Input phone number" pattern="[0-9]+" required>
                        </div>

                        <div class="mb-3">
                            <label for="company_location" class="form-label fw-semibold">
                                Location <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="company_location"
                                name="company_location" placeholder="Input company location" required>
                        </div>

                        <div class="mb-3">
                            <label for="industries_id" class="form-label fw-semibold">
                                Industry <span class="text-danger">*</span>
                            </label>
                            <select id="industries_id" name="industries_id" class="form-select form-select-lg" required>
                                <option value="">Select Industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="company_description" class="form-label fw-semibold">
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="company_description" name="company_description" rows="3"
                                placeholder="Describe your company" required></textarea>
                        </div>

                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-primary-custom btn-lg text-white">
                                Create Account
                            </button>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-secondary btn-lg" data-prev="step2">
                                Back
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('register-form');
            let currentStep = 'step1';

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('birth_date').setAttribute('max', today);

            const phoneInputs = ['phone_number', 'company_phone_number'];
            phoneInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', function(e) {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                }
            });

            $('.select2-multiple').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select options...',
                allowClear: true,
                closeOnSelect: false
            });

            // Portfolio preview
            document.getElementById('portfolios').addEventListener('change', function(e) {
                const preview = document.getElementById('portfolio-preview');
                preview.innerHTML = '';

                Array.from(e.target.files).forEach(file => {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'd-inline-block me-2 mb-2 position-relative';
                        div.style.width = '100px';
                        div.style.height = '100px';

                        if (file.type.startsWith('image/')) {
                            div.innerHTML = `
                                <img src="${event.target.result}" 
                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 2px solid #dee2e6;">
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; font-size: 10px; padding: 2px 4px; border-radius: 0 0 6px 6px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                    ${file.name}
                                </div>
                            `;
                        } else {
                            div.innerHTML = `
                                <div style="width: 100%; height: 100%; border-radius: 8px; border: 2px solid #dee2e6; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8f9fa;">
                                    <i class="bi bi-file-earmark-text fs-2 text-muted"></i>
                                    <small class="text-muted mt-1" style="font-size: 9px; text-align: center; padding: 0 4px; word-break: break-all;">${file.name}</small>
                                </div>
                            `;
                        }

                        preview.appendChild(div);
                    };

                    reader.readAsDataURL(file);
                });
            });

            document.getElementById('togglePassword').addEventListener('click', function() {
                const field = document.getElementById('password');
                field.type = field.type === 'password' ? 'text' : 'password';
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });

            document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
                const field = document.getElementById('password_confirmation');
                field.type = field.type === 'password' ? 'text' : 'password';
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });

            async function checkEmailAvailability(email) {
                try {
                    const response = await fetch('{{ route('check.email') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    });
                    return await response.json();
                } catch (error) {
                    return {
                        available: false,
                        message: 'Error checking email'
                    };
                }
            }

            document.querySelectorAll('[data-next]').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const nextStep = btn.dataset.next;

                    if (currentStep === 'step1') {
                        const isValid = await validateStep1();
                        if (!isValid) return;
                    }

                    if (nextStep === 'step3') {
                        const role = document.querySelector('input[name="role"]:checked');
                        if (!role) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Role Required',
                                text: 'Please select your role',
                                confirmButtonColor: 'var(--primary-blue)'
                            });
                            return;
                        }
                        goTo(role.value === 'user' ? 'step3-candidate' : 'step3-company');
                        toggleFields(role.value);
                        return;
                    }

                    if (nextStep === 'step4-candidate') {
                        if (!validateStep3Candidate()) return;
                    }

                    goTo(nextStep);
                });
            });

            document.querySelectorAll('[data-prev]').forEach(btn => {
                btn.addEventListener('click', () => goTo(btn.dataset.prev));
            });

            document.querySelectorAll('input[name="role"]').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    document.querySelectorAll('.role-option').forEach(opt => {
                        opt.style.borderColor = '#e5e7eb';
                        opt.style.backgroundColor = 'white';
                    });
                    const label = e.target.parentElement.querySelector('.role-option');
                    label.style.borderColor = 'var(--primary-blue)';
                    label.style.backgroundColor = 'var(--bg-blue)';
                });
            });

            function goTo(stepId) {
                document.querySelectorAll('.step-content').forEach(step => step.classList.remove('active'));
                document.getElementById(stepId).classList.add('active');
                currentStep = stepId;
                document.querySelector('.login-box').scrollTop = 0;

                setTimeout(() => {
                    $('.select2-multiple').select2({
                        theme: 'bootstrap-5',
                        placeholder: 'Select options...',
                        allowClear: true,
                        closeOnSelect: false
                    });
                }, 100);
            }

            async function validateStep1() {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const passwordConfirm = document.getElementById('password_confirmation').value;
                const emailError = document.getElementById('email-error');
                const emailInput = document.getElementById('email');

                emailError.style.display = 'none';
                emailInput.classList.remove('is-invalid');

                if (!name || !email || !password || !passwordConfirm) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill in all fields',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return false;
                }

                if (!email.includes('@')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address with @',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return false;
                }

                if (password.length < 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Password must be at least 8 characters',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return false;
                }

                if (password !== passwordConfirm) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Passwords do not match',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return false;
                }

                const emailCheck = await checkEmailAvailability(email);
                Swal.close();

                if (!emailCheck.available) {
                    emailInput.classList.add('is-invalid');
                    emailError.textContent = emailCheck.message || 'This email is already registered';
                    emailError.style.display = 'block';

                    Swal.fire({
                        icon: 'error',
                        title: 'Email Already Registered',
                        text: emailCheck.message ||
                            'This email is already registered. Please use a different email or login.',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return false;
                }

                return true;
            }

            function validateStep3Candidate() {
                const phoneNumber = document.getElementById('phone_number').value.trim();
                const gender = document.getElementById('gender').value;
                const birthDate = document.getElementById('birth_date').value;

                if (!phoneNumber || !gender || !birthDate) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Required Fields',
                        text: 'Please fill in Phone Number, Gender and Birth Date',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return false;
                }

                return true;
            }

            function toggleFields(role) {
                const candidateBasic = document.querySelectorAll(
                    '#step3-candidate input, #step3-candidate select, #step3-candidate textarea');
                const candidateAdditional = document.querySelectorAll(
                    '#step4-candidate input:not([type="file"]), #step4-candidate select:not(.select2-multiple), #step4-candidate textarea'
                );
                const company = document.querySelectorAll(
                    '#step3-company input, #step3-company select, #step3-company textarea');
                const candidateSelect2 = document.querySelectorAll('#step4-candidate .select2-multiple');

                [...candidateBasic, ...candidateAdditional, ...company].forEach(field => {
                    field.disabled = true;
                    field.removeAttribute('required');
                });
                candidateSelect2.forEach(field => $(field).prop('disabled', true));

                if (role === 'user') {
                    candidateBasic.forEach(field => field.disabled = false);
                    document.getElementById('phone_number').setAttribute('required', 'required');
                    document.getElementById('gender').setAttribute('required', 'required');
                    document.getElementById('birth_date').setAttribute('required', 'required');
                    candidateAdditional.forEach(field => field.disabled = false);
                    candidateSelect2.forEach(field => $(field).prop('disabled', false));
                    document.getElementById('portfolios').disabled = false;
                } else {
                    company.forEach(field => {
                        field.disabled = false;
                        if (['company_name', 'company_phone_number', 'company_location', 'industries_id',
                                'company_description'
                            ].includes(field.id)) {
                            field.setAttribute('required', 'required');
                        }
                    });
                }
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const role = document.querySelector('input[name="role"]:checked')?.value;

                if (role === 'user') {
                    const phoneNumber = document.getElementById('phone_number').value.trim();
                    const gender = document.getElementById('gender').value;
                    const birthDate = document.getElementById('birth_date').value;

                    if (!phoneNumber || !gender || !birthDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Required Fields Missing',
                            text: 'Phone Number, Gender and Birth Date are required',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                        return;
                    }
                }

                try {
                    const response = await fetch('{{ route('register.post') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json',
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful!',
                            text: data.message || 'Welcome!',
                            confirmButtonColor: 'var(--primary-blue)',
                            allowOutsideClick: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                        window.location.href = data.redirect || '/';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: data.message || 'Something went wrong',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'A network error occurred. Please try again.',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                }
            });
        });
    </script>
@endsection
