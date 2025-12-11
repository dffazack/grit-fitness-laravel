<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // 1. Arahkan user ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Google mengembalikan user ke sini setelah login sukses
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user ini sudah ada di database (berdasarkan email)
            $user = User::where('email', $googleUser->getEmail())->first();

            if (! $user) {
                // KASUS A: User Baru (Belum pernah daftar)
                // Kita buatkan akun otomatis & LANGSUNG VERIFIKASI
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, // Tidak ada password
                    'email_verified_at' => now(), // <--- INI JAWABANNYA! Langsung kita anggap verified
                    'role' => 'guest', // Default role kamu
                    'membership_status' => 'non-member',
                    // 'phone' => null, // Phone mungkin kosong dulu
                ]);
            } else {
                // KASUS B: User Lama (Sudah pernah daftar manual atau login google sebelumnya)
                // Kita update google_id-nya biar terhubung
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(), // Verifikasi jika belum
                ]);
            }

            // Login-kan user
            Auth::login($user);

            // Redirect ke halaman yang sesuai
            return redirect()->route('homepage')
                ->with('success', 'Login Google Berhasil! Selamat datang, '.$user->name.'.');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login Google Gagal: '.$e->getMessage());
        }
    }
}
