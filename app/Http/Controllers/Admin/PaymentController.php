<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User; // Pastikan ini ada
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    /**
     * Menampilkan daftar pembayaran yang PERLU VALIDASI (pending).
     */
    public function index(Request $request)
    {
        // Modifikasi query: Hanya ambil yang statusnya 'pending'
        $query = Transaction::where('status', 'pending')
                            ->with(['user', 'membership']); // Muat relasi user & membership

        // Logika filter (opsional, tapi bagus untuk masa depan)
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $transactions = $query->latest()->paginate(15);
        
        return view('admin.payments.index', compact('transactions'));
    }

    /**
     * Menyetujui (approve) sebuah transaksi.
     */
    public function approve(Transaction $transaction)
    {
        // 1. Ubah status transaksi
        $transaction->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth('admin')->id(), // Catat siapa admin yang approve
        ]);

        // 2. Perbarui status member di tabel 'users'
        $user = $transaction->user;
        if ($user && $transaction->membership) {
            $duration = $transaction->membership->duration_months;
            
            $user->update([
                'membership_status' => 'active',
                'membership_package' => $transaction->package,
                // Hitung tanggal kedaluwarsa dari SEKARANG
                'membership_expiry' => now()->addMonths($duration), 
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil disetujui.');
    }

    /**
     * Menolak (reject) sebuah transaksi.
     */
    public function reject(Transaction $transaction)
    {
        // 1. Ubah status transaksi
        $transaction->update([
            'status' => 'rejected',
        ]);
        
        // (Opsional) Anda bisa tambahkan logika untuk mengirim notifikasi ke user

        return back()->with('success', 'Pembayaran berhasil ditolak.');
    }
}
