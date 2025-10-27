<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index(Request $request)
    {
        $query = Transaction::with('user');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $transactions = $query->latest()->paginate(15);
        
        return view('admin.payments.index', compact('transactions'));
    }
    
    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        
        // Update user membership
        $expiryDate = Carbon::now()->addMonths(12);
        
        $transaction->user->update([
            'membership_status' => 'active',
            'membership_package' => $transaction->package,
            'membership_expiry' => $expiryDate,
            'role' => 'member',
        ]);
        
        return back()->with('success', 'Pembayaran berhasil disetujui');
    }
    
    public function reject(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $transaction->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_note' => $request->note,
        ]);
        
        $transaction->user->update([
            'membership_status' => 'non-member',
        ]);
        
        return back()->with('success', 'Pembayaran ditolak');
    }
}

