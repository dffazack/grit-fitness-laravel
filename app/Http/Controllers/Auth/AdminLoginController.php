<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Menampilkan form login admin.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Menangani upaya login admin.
     */
    public function login(Request $request)
{
    // 1. Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');
    $remember = $request->filled('remember');

    // 2. Coba lakukan autentikasi MENGGUNAKAN GUARD 'admin'
    if (Auth::guard('admin')->attempt($credentials, $remember)) {
        
        // 3. Autentikasi berhasil (pasti admin, karena dari tabel admins)
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard')); // Arahkan ke dashboard admin
    }

    // 4. Jika autentikasi gagal (email/password salah)
    return back()->withErrors([
        'email' => 'Email atau password yang Anda masukkan salah.',
    ])->withInput($request->only('email', 'remember'));
}
}