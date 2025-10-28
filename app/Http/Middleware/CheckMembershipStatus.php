<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMembershipStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if membership is expired
        if ($user->membership_status === 'expired') {
            return redirect()->route('member.payment')
                ->with('error', 'Membership Anda telah berakhir. Silakan perpanjang membership.');
        }

        // Check if membership is pending
        if ($user->membership_status === 'pending') {
            return redirect()->route('member.dashboard')
                ->with('info', 'Pembayaran Anda sedang diproses oleh admin.');
        }

        // Check if user is not a member
        if ($user->membership_status === 'non-member') {
            return redirect()->route('membership')
                ->with('info', 'Silakan pilih paket membership terlebih dahulu.');
        }

        return $next($request);
    }
}
