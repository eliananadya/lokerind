<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // ✅ Redirect berdasarkan role
                return match ($user->role->name) {
                    'super_admin' => redirect('/admin'),
                    'company' => redirect()->route('company.dashboard'),
                    'user' => redirect()->route('index.home'),
                    default => redirect()->route('login'),
                };
            }
        }

        return $next($request);
    }
}
