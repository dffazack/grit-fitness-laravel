<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    // GANTI METHOD INI DENGAN KODE BARU
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check membership status for members
            if ($user->membership_status === 'non-member') {
                return redirect()->route('membership')
                    ->with('info', 'Silakan pilih paket membership untuk melanjutkan.');
            }
            
            if ($user->membership_status === 'pending') {
                return redirect()->route('member.dashboard')
                    ->with('info', 'Pembayaran Anda sedang diproses. Kami akan menghubungi Anda segera.');
            }
            
            // Default redirect for active members
            return redirect()->route('member.dashboard')
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
            ->with('success', 'Anda telah logout.');
    }
}