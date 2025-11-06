<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        $user = Auth::user();

        // Jika guard admin aktif, lewati pengecekan role member
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Jika user bukan member, tolak akses
        if (!$user || $user->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}