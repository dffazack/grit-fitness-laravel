<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        // Manual check: jika admin sudah login, redirect ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt login dengan guard 'admin'
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke admin dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        // PENTING: Logout dari guard ADMIN
        Auth::guard('admin')->logout();

        // Invalidate session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect ke halaman login ADMIN
        return redirect()->route('admin.login');
    }
}