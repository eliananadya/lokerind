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
                <h1>Buat Password Baru</h1>
                <p>Masukkan password baru untuk akun Anda.<br>Pastikan password kuat dan mudah diingat.</p>
            </div>
        </div>

        <div class="form-overlay">
            <div class="login-box">
                <div class="mb-4">
                    <h2 class="fw-bold mb-2">Reset Password</h2>
                    <p class="text-muted">Buat password baru Anda</p>
                </div>

                <form id="reset-password-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Email (disabled) -->
                    <div class="mb-3">
                        <label for="email_display" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control form-control-lg" value="{{ $email }}" disabled>
                    </div>

                    <!-- Password Baru -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password Baru</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control form-control-lg" id="password" name="password"
                                placeholder="Minimal 8 karakter" required>
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>Password minimal 8 karakter
                        </small>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control form-control-lg" id="password_confirmation"
                                name="password_confirmation" placeholder="Ulangi password" required>
                            <i class="bi bi-eye-slash password-toggle" id="togglePasswordConfirm"></i>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-1">
                        <button type="submit" class="btn btn-primary-custom btn-lg text-white mt-3">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('reset-password-form');

            // Toggle show/hide password
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

            // Handle form submit
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const password = document.getElementById('password').value;
                const passwordConfirm = document.getElementById('password_confirmation').value;

                // Validasi harus diisi
                if (!password || !passwordConfirm) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Reset Password Failed',
                        text: 'Please fill in all fields',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return;
                }

                // Validasi password match
                if (password !== passwordConfirm) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Reset Password Failed',
                        text: 'Passwords do not match',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return;
                }

                try {
                    const response = await fetch('{{ route('password.update') }}', {
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
                        // Reset berhasil
                        await Swal.fire({
                            icon: 'success',
                            title: 'Password Reset Successful!',
                            text: data.message || 'Password has been reset successfully',
                            confirmButtonColor: 'var(--primary-blue)',
                            allowOutsideClick: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                        window.location.href = data.redirect;
                    } else {
                        // Reset gagal
                        Swal.fire({
                            icon: 'error',
                            title: 'Reset Password Failed',
                            text: data.message || 'Invalid or expired token',
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
