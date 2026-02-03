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
                <h1>Temukan Peluang Karir<br>Impian Anda</h1>
                <p>Hubungkan dengan ribuan perusahaan terkemuka dan temukan<br>pekerjaan yang sesuai dengan keahlian dan
                    passion Anda.</p>
            </div>
        </div>

        <div class="form-overlay">
            <div class="login-box">
                <div class="mb-4">
                    <h2 class="fw-bold mb-2">Welcome Back</h2>
                    <p class="text-muted">Log in to start</p>
                </div>
                <form id="login-form">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email"
                            placeholder="Input your email" required autofocus autocomplete="username">
                    </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control form-control-lg" id="password" name="password"
                                placeholder="Input your password" required autocomplete="current-password">
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('password.request') }}"
                            class="text-decoration-none text-primary-custom fw-semibold">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary-custom btn-lg text-white">
                            Login
                        </button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center">
                        <span class="text-muted">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="text-decoration-none text-primary-custom fw-semibold">
                            Sign up here
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('login-form');

            // Toggle show/hide password
            document.getElementById('togglePassword').addEventListener('click', function() {
                const field = document.getElementById('password');
                field.type = field.type === 'password' ? 'text' : 'password';
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });

            // Show session message if exists
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: 'var(--primary-blue)',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // Handle form submit
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;

                // Validasi field harus diisi
                if (!email || !password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: 'Please fill in all fields',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return;
                }

                try {
                    const response = await fetch('{{ route('login') }}', {
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
                        // Login berhasil
                        await Swal.fire({
                            icon: 'success',
                            title: 'Login Successful!',
                            text: data.message || 'Welcome back!',
                            confirmButtonColor: 'var(--primary-blue)',
                            allowOutsideClick: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                        window.location.href = data.redirect;
                    } else {
                        // Login gagal
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: data.message || 'Invalid email or password',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                    }
                } catch (error) {
                    // Network error
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
