<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Admin yang sudah login mencoba akses halaman login admin
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                // Member yang sudah login mencoba akses halaman login member
                if ($guard === null || $guard === 'web') {
                    return redirect()->route('member.dashboard');
                }
            }
        }

        return $next($request);
    }
}
