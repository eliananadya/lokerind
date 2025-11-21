@extends('layouts.main')

@section('content')
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Left Side - Image -->
            <div class="col-md-6 bg-light d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="20" width="160" height="160" rx="10" stroke="#999" stroke-width="4"
                            fill="none" />
                        <circle cx="80" cy="70" r="15" fill="#999" />
                        <path d="M40 140 L80 100 L120 130 L160 90" stroke="#999" stroke-width="4" stroke-linecap="round"
                            stroke-linejoin="round" fill="none" />
                    </svg>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="col-md-6 bg-white d-flex align-items-center">
                <div class="w-100 px-5">
                    <div class="mb-5">
                        <h2 class="fw-bold mb-2">Welcome Back</h2>
                        <p class="text-muted">Log in to start</p>
                    </div>

                    <form>
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email"
                                placeholder="input your email">
                        </div>

                        <!-- Password -->
                        <div class="mb-2">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control form-control-lg" id="password"
                                placeholder="input your password">
                        </div>

                        <!-- Forgot Password -->
                        <div class="text-end mb-4">
                            <a href="#" class="text-decoration-none text-muted small">Forgot Password?</a>
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-secondary btn-lg">Login</button>
                        </div>

                        <!-- Sign Up Link -->
                        <div class="text-center">
                            <span class="text-muted">Don't have an account?</span>
                            <a href="/register" class="text-decoration-none">Sign up here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
