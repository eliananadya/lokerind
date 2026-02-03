<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request)
    {
        // ✅ Logout user jika sudah login
        if (Auth::check()) {
            Auth::logout();
        }

        return view('auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->query('email')
        ]);
    }

    /**
     * Handle an incoming new password request (AJAX).
     */
    public function store(Request $request)
    {
        // ✅ Logout user jika sudah login
        if (Auth::check()) {
            Auth::logout();
        }

        // Validation
        try {
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::min(8)],
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email tidak terdaftar',
                'password.required' => 'Password baru wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Return JSON response
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset!   Silakan login dengan password baru Anda.',
                'redirect' => route('login')
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal reset password.Token mungkin sudah kadaluarsa atau tidak valid. Silakan minta link reset baru.',
            'errors' => ['email' => ['Token tidak valid atau sudah kadaluarsa']]
        ], 400);
    }
}
