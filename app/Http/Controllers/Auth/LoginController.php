<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (request()->routeIs('admin.login')) {
            return view('auth.admin-login');
        }
        return view('auth.login');
    }
    
    // GANTI METHOD INI DENGAN KODE BARU
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Handle Admin Login
        if ($request->routeIs('admin.login.submit')) {
            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang kembali, ' . Auth::guard('admin')->user()->name . '!');
            }

            return back()->withErrors([
                'email' => 'Email atau password salah untuk akun admin.',
            ])->onlyInput('email');
        }

        // Handle Member Login
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();

            if ($user->membership_status === 'non-member' || $user->isGuest()) {
                return redirect()->route('membership')
                    ->with('info', 'Akun Anda sudah terdaftar. Silakan pilih paket membership untuk melanjutkan.');
            }
            
            if ($user->isPending()) {
                return redirect()->route('member.dashboard')
                    ->with('info', 'Pembayaran Anda sedang diproses. Kami akan menghubungi Anda segera.');
            }
            
            return redirect()->route('member.dashboard')
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
            ->with('success', 'Anda telah logout.');
    }
}