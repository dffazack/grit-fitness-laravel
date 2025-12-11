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

        if (! $user) {
            return redirect()->route('login');
        }

        // Special handling for pending members on the dashboard
        if ($user->membership_status === 'pending' && $request->route()->named('member.dashboard')) {
            // Flash the message and let them see the dashboard.
            session()->flash('info', 'Pembayaran Anda sedang diproses oleh admin.');

            return $next($request);
        }

        $allowedRoutes = [
            'member.payment',
            'member.payment.store',
            'membership',
            'logout',
        ];

        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        if ($user->membership_status === 'non-member') {
            return redirect('/membership')->with('info', 'Silakan pilih paket membership terlebih dahulu.');
        }

        if ($user->membership_status === 'pending') {
            // For any other route, redirect to the dashboard
            return redirect('/member/dashboard');
        }

        if ($user->membership_status === 'expired') {
            return redirect('/member/payment')->with('error', 'Membership Anda telah berakhir. Silakan perpanjang membership.');
        }

        // This is for 'active' members
        return $next($request);
    }
}
