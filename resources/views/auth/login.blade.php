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
                            <button type="submit" class="btn btn-primary-custom btn-lg text-white">Login</button>
                        </div>

                        <!-- Sign Up Link -->
                        <div class="text-center">
                            <span class="text-muted">Don't have an account?</span>
                            <a href="#" class="text-decoration-none text-primary-custom fw-semibold">Sign up here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
