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
        // This middleware is for the 'web' guard.
        // It should only check the currently authenticated user on this guard.
        if (!Auth::check()) {
            // If no user is authenticated on the web guard, redirect to login.
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if the user has the required role.
        if ($user->role !== $role) {
            // If not, forbid access.
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}