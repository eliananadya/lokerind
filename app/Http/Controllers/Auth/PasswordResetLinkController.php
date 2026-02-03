<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request (AJAX).
     */
    // public function store(Request $request)
    // {
    //     // ✅ Validation dengan custom messages
    //     $request->validate([
    //         'email' => ['required', 'email', 'exists:users,email'],
    //     ], [
    //         'email.required' => 'Email wajib diisi',
    //         'email.email' => 'Format email tidak valid',
    //         'email.exists' => 'Email tidak terdaftar dalam sistem kami',
    //     ]);

    //     // ✅ Send reset link
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     // ✅ Return JSON response untuk AJAX
    //     if ($request->expectsJson() || $request->ajax()) {
    //         if ($status === Password::RESET_LINK_SENT) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Link reset password telah dikirim ke email Anda.  Silakan cek inbox atau folder spam.'
    //             ], 200);
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal mengirim link reset password. Silakan coba lagi.',
    //             'errors' => ['email' => [__($status)]]
    //         ], 500);
    //     }

    //     // ✅ Fallback untuk non-AJAX (original Breeze behavior)
    //     if ($status === Password::RESET_LINK_SENT) {
    //         return back()->with('status', __($status));
    //     }

    //     throw ValidationException::withMessages([
    //         'email' => [__($status)],
    //     ]);
    // }
    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar dalam sistem kami',
        ]);

        // ✅ Send reset link via email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // ✅ Return JSON response untuk AJAX
        if ($request->expectsJson() || $request->ajax()) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.',
                    'redirect' => route('login') // Redirect ke login page
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim link reset password. Silakan coba lagi.',
                'errors' => ['email' => [__($status)]]
            ], 422);
        }

        // ✅ Fallback untuk non-AJAX
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
