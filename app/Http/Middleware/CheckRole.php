<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // ✅ Check if user has role relation and role name matches
        if ($user->role && $user->role->name === $role) {
            return $next($request);
        }

        // ✅ If role doesn't match, deny access
        abort(403, 'Anda tidak memiliki akses ke halaman ini.  Role Anda: ' .
            ($user->role ? $user->role->name : 'No role'));
    }
}
