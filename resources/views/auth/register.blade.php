@php
    $hideNavbar = true;
    $hideFooter = true;
@endphp
@extends('layouts.main')

@section('content')
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Left Side - Image -->
            <div class="col-md-6 left-side d-flex align-items-center justify-content-center">
                <div class="image-container">
                    <img src="{{ asset('assets/images/job.png') }}" alt="Job Illustration" class="img-fluid">
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="col-md-6 bg-white d-flex align-items-center">
                <div class="w-100 px-5 py-4">

                    <!-- Step 1: Account Details -->
                    <div id="step1" class="step-content">
                        <div class="mb-5">
                            <h2 class="fw-bold mb-2">Create a Account</h2>
                            <p class="text-muted">Join for free</p>
                        </div>

                        <form id="form-step1">
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    placeholder="input your email" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password"
                                    placeholder="input your password" required>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" class="form-control form-control-lg" id="confirm_password"
                                    name="confirm_password" placeholder="input your password" required>
                                <div class="invalid-feedback" id="password-error">
                                    Passwords do not match!
                                </div>
                            </div>

                            <!-- Create Account Button -->
                            <div class="d-grid mb-4">
                                <button type="button" class="btn btn-primary-custom btn-lg text-white"
                                    onclick="goToStep2()">
                                    Create Account
                                </button>
                            </div>

                            <!-- Terms -->
                            <p class="text-muted small text-center mb-4">
                                Dengan mendaftar Anda menyetujui Syarat dan ketentuan & Kebijakan Privasi
                            </p>

                            <!-- Sign In Link -->
                            <div class="text-center">
                                <span class="text-muted">Already have an account?</span>
                                <a href="#" class="text-decoration-none text-primary-custom fw-semibold">Sign In
                                    here</a>
                            </div>
                        </form>
                    </div>

                    <!-- Step 2: Choose Role -->
                    <div id="step2" class="step-content" style="display: none;">
                        <div class="mb-5">
                            <h2 class="fw-bold mb-2">Create a Account</h2>
                            <p class="text-muted">Choose your role</p>
                        </div>

                        <form id="form-step2">
                            <!-- Role Selection -->
                            <div class="mb-4">
                                <div class="role-card mb-3" onclick="selectRole('candidate')">
                                    <input type="radio" name="role" id="role-candidate" value="candidate"
                                        class="d-none">
                                    <label for="role-candidate" class="role-label w-100">
                                        <div class="card role-option">
                                            <div class="card-body text-center py-4">
                                                <i class="bi bi-person-circle fs-1 text-primary-custom mb-2"></i>
                                                <h5 class="fw-semibold">I'm Candidate</h5>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="role-card" onclick="selectRole('company')">
                                    <input type="radio" name="role" id="role-company" value="company" class="d-none">
                                    <label for="role-company" class="role-label w-100">
                                        <div class="card role-option">
                                            <div class="card-body text-center py-4">
                                                <i class="bi bi-bank fs-1 text-primary-custom mb-2"></i>
                                                <h5 class="fw-semibold">I'm Company</h5>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Let's Get Start Button -->
                            <div class="d-grid mb-3">
                                <button type="button" class="btn btn-primary-custom btn-lg text-white" id="btn-next-step"
                                    onclick="goToStep3()" disabled>
                                    Let's Get Start
                                </button>
                            </div>

                            <!-- Back Button -->
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="goToStep1()">
                                    Back
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Step 3: Additional Info (will be shown based on role) -->
                    <div id="step3" class="step-content" style="display: none;">
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            let selectedRole = '';

            // Validate Step 1
            function goToStep2() {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                const errorDiv = document.getElementById('password-error');

                // Reset error
                errorDiv.classList.remove('show');
                document.getElementById('confirm_password').classList.remove('is-invalid');

                // Validate
                if (!email || !password || !confirmPassword) {
                    alert('Please fill all fields!');
                    return;
                }

                if (password !== confirmPassword) {
                    errorDiv.classList.add('show');
                    document.getElementById('confirm_password').classList.add('is-invalid');
                    return;
                }

                // Go to step 2
                document.getElementById('step1').style.display = 'none';
                document.getElementById('step2').style.display = 'block';
            }

            // Select Role
            function selectRole(role) {
                selectedRole = role;
                document.getElementById('role-' + role).checked = true;
                document.getElementById('btn-next-step').disabled = false;
            }

            // Go to Step 3 (will be different based on role)
            function goToStep3() {
                if (!selectedRole) {
                    alert('Please select your role!');
                    return;
                }

                // Here you can load different form based on role
                alert('Selected role: ' + selectedRole + '\n\nNext: Form untuk ' + selectedRole);
                // You can navigate or show different form here
            }

            // Back to Step 1
            function goToStep1() {
                document.getElementById('step2').style.display = 'none';
                document.getElementById('step1').style.display = 'block';
            }
        </script>
    @endpush
@endsection
