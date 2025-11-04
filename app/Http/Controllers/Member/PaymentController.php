<?php


namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $packages = MembershipPackage::where('is_active', true)->get();
        $transactions = Auth::user()->transactions()->latest()->get();
        
        return view('member.payment', compact('packages', 'transactions'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package' => 'required|in:basic,premium,vip',
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $package = MembershipPackage::where('type', $validated['package'])->first();
        
        // Upload payment proof
        $proofPath = $request->file('proof')->store('payments', 'public');
        
        // Create transaction
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
            'package' => $validated['package'],
            'amount' => $package->price,
            'proof_url' => $proofPath,
            'status' => 'pending',
        ]);
        
        // Update user membership status
        Auth::user()->update([
            'membership_status' => 'pending',
            'membership_package' => $validated['package'],
        ]);
        
        return back()->with('success', 'Pembayaran berhasil diajukan. Menunggu validasi admin.');
    }
}

