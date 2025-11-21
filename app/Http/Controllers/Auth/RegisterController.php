<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered; // Pastikan baris ini ada (sudah ada di kodemu)
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // 2. Buat User Baru di Database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'guest', // Default role
            'membership_status' => 'non-member',
        ]);
        
        // ============================================================
        // PERBAIKAN: Tambahkan kode ini agar email otomatis terkirim
        // ============================================================
        event(new Registered($user));
        // ============================================================

        // 3. Login Otomatis
        Auth::login($user);
        
        // 4. Redirect ke Halaman Notice "Cek Email Anda"
        return redirect()->route('verification.notice')
            ->with('success', 'Akun berhasil dibuat! Silakan cek inbox email Anda untuk verifikasi.');
    }
}