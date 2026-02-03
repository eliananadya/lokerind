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
                <h1>Reset Your Password</h1>
                <p>Masukkan email Anda dan kami akan mengirimkan<br>link untuk reset password Anda.</p>
            </div>
        </div>

        <div class="form-overlay">
            <div class="login-box">
                <div class="mb-4">
                    <h2 class="fw-bold mb-2">Forgot Password?</h2>
                    <p class="text-muted">No worries, we'll send you reset instructions.</p>
                </div>

                <form id="forgot-password-form">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email"
                            placeholder="Input your email" required autofocus autocomplete="email">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary-custom btn-lg text-white mt-3">
                            Send Reset Link
                        </button>
                    </div>

                    <!-- Back to Login Link -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none text-primary-custom fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('forgot-password-form');

            // Show session message if exists
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('status') }}',
                    confirmButtonColor: 'var(--primary-blue)',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            // Handle form submit
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const email = document.getElementById('email').value.trim();

                // Validasi email harus diisi
                if (!email) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Please enter your email address',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return;
                }

                try {
                    const response = await fetch('{{ route('password.email') }}', {
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
                        // Email berhasil dikirim
                        await Swal.fire({
                            icon: 'success',
                            title: 'Email Sent!',
                            text: data.message ||
                                'We have sent password reset link to your email',
                            confirmButtonColor: 'var(--primary-blue)',
                            allowOutsideClick: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        window.location.href = data.redirect || '{{ route('login') }}';
                    } else {
                        // Email gagal / tidak terdaftar
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: data.message || 'Email not found in our records',
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
