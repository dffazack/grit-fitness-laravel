<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $packages = MembershipPackage::where('is_active', true)->get();
        $transactions = Auth::user()->transactions()->latest()->get();
        
        return view('member.payment', compact('packages', 'transactions'));
    }
    
    public function submitPayment(Request $request)
    {
        $validated = $request->validate([
            'membership_package_id' => 'required|exists:membership_packages,id',
            'proof' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);
        
        DB::beginTransaction();
        try {
            $package = MembershipPackage::findOrFail($validated['membership_package_id']);
            
            // Upload payment proof
            $proofPath = $request->file('proof')->store('payments', 'public');
            
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
                'membership_package_id' => $package->id,
                'amount' => $package->price,
                'proof_url' => $proofPath,
                'status' => 'pending',
            ]);
            
            // Update user membership status
            Auth::user()->update([
                'membership_status' => 'pending',
                'membership_package_id' => $package->id,
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Pembayaran berhasil diajukan. Menunggu validasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment submission failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengajukan pembayaran. Silakan coba lagi.');
        }
    }
}
