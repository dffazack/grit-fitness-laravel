<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $packages = MembershipPackage::where('is_active', true)->get();
        $transactions = Auth::user()->transactions()->latest()->get();
        
        return view('member.payment', compact('packages', 'transactions'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package' => ['required', \Illuminate\Validation\Rule::in(array_keys(MembershipPackage::TYPES))],
            'proof' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);
        
        // Pengecekan keamanan ganda
        if (!$request->hasFile('proof') || !$request->file('proof')->isValid()) {
            return back()
                ->withErrors(['proof' => 'Upload bukti pembayaran gagal. File mungkin rusak atau bukan gambar.'])
                ->withInput();
        }
        
        $package = MembershipPackage::where('type', $validated['package'])->firstOrFail();
        
        // ==============================================================
        // !! PERUBAHAN LOGIKA PENYIMPANAN FILE !!
        // ==============================================================
        
        try {
            $file = $request->file('proof');
            
            // 1. Buat nama file unik
            $filename = 'payments/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
            
            // 2. Baca isi file ke memori, lalu tulis ke disk 'public'
            // Ini menghindari penggunaan getPathname()
            $isSuccess = Storage::disk('public')->put($filename, $file->get());

            if (!$isSuccess) {
                // Gagal menyimpan karena alasan yang tidak diketahui
                throw new \Exception('Gagal menyimpan file ke disk.');
            }

        } catch (\Exception $e) {
            // Tangkap error jika $file->get() gagal (mungkin karena file benar-benar 0-byte)
             return back()
                ->withErrors(['proof' => 'Terjadi error saat memproses file. Pastikan file tidak 0-byte.'])
                ->withInput();
        }

        // ==============================================================
        
        // Create transaction
        Transaction::create([
            'user_id' => Auth::id(),
            'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
            'membership_package_id' => $package->id,
            'amount' => $package->price,
            'proof_url' => $filename, // Simpan path yang kita buat
            'status' => 'pending',
        ]);
        
        // !! TAMBAHAN: Update user membership_status ke 'pending'
        $user = Auth::user();
        $user->membership_status = 'pending';
        $user->save();
        
        return back()->with('success', 'Pembayaran berhasil diajukan. Menunggu validasi admin.');
    }
}