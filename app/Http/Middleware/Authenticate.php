<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            
            // TAMBAHKAN INI:
            // Jika URL yang diminta berawalan 'admin/'
            if ($request->is('admin') || $request->is('admin/*')) {
                // Arahkan ke halaman login admin
                return route('admin.login');
            }

            // Jika tidak, gunakan default (halaman login member)
            return route('login');
        }
        
        return null;
    }
}