<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     * Mencegah member biasa mengakses area admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika ada user member yang login (bukan admin)
        if (Auth::guard('web')->check() && !Auth::guard('admin')->check()) {
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}