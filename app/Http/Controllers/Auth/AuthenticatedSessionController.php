<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            // Authenticate the user (login process)
            $request->authenticate();

            // Regenerate the session ID to prevent session fixation
            $request->session()->regenerate();

            $user = Auth::user();
            $userName = $user->name;
            $roleName = $user->role?->name;

            // Determine redirect URL based on role
            $redirectUrl = route('index.home'); // Default redirect ke home

            // Jika role company, redirect ke dashboard company
            if ($roleName === 'company') {
                $redirectUrl = route('company.dashboard');
            }
            // Jika role super_admin, redirect ke admin
            elseif ($roleName === 'super_admin') {
                $redirectUrl = '/admin';
            }
            // Role 'user' (candidate) tetap ke home
            elseif ($roleName === 'user') {
                $redirectUrl = route('index.home');
            }

            // Check if request is AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back, ' . $userName . '!',
                    'redirect' => $redirectUrl
                ], 200);
            }

            // Redirect based on role (for normal form submission)
            return redirect($redirectUrl);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if request is AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'These credentials do not match our records.',
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Check if request is AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'You have been logged out successfully.',
                'redirect' => route('index.home')
            ], 200);
        }

        return redirect()->route('index.home');
    }
}
