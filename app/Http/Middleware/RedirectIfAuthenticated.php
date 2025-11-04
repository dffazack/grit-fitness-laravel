<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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
                
                // GANTI LOGIKA DEFAULT DENGAN INI:
                $user = Auth::user();
                
                // Jika admin sudah login, arahkan ke admin dashboard
                if ($user->role === 'admin') {
                    return redirect(route('admin.dashboard'));
                }

                // Jika member sudah login, arahkan ke member dashboard
                if ($user->role === 'member') {
                    return redirect(route('member.dashboard')); 
                }

            }
        }

        return $next($request);
    }
}