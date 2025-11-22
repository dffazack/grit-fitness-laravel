<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMembershipStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Pastikan user login (Jaga-jaga)
        if (!$user) {
            return redirect()->route('login');
        }

        // =================================================================
        // PERBAIKAN UTAMA: DAFTAR PENGECUALIAN (WHITELIST)
        // =================================================================
        // Kita harus mengizinkan user mengakses halaman 'payment' atau 'membership'
        // meskipun status mereka 'pending' atau 'expired', supaya tidak loop.
        
        // Ganti 'member.payment' dan 'membership' sesuai nama route kamu
        $allowedRoutes = [
            'member.payment', 
            'member.payment.store', 
            'membership',
            'logout', // Penting! Biar user bisa logout kalau nyangkut
        ];

        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }
        // =================================================================

        // 2. Cek Status Membership
        if ($user->membership_status !== 'active') {
            
            // Jika Pending, lempar ke halaman pembayaran member
            if ($user->membership_status === 'pending') {
                return redirect()->route('member.payment')
                    ->with('warning', 'Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran.');
            }

            // Jika Expired atau belum punya, lempar ke halaman pilih paket
            return redirect()->route('membership')
                ->with('warning', 'Membership Anda tidak aktif. Silakan beli paket.');
        }

        return $next($request);
    }
}