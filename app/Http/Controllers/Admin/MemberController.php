<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{

    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('membership_status', $request->status);
        }
        
        // KITA PERBARUI INI:
        // Tambahkan with('membership') dan with('transactions')
        // 'membership' untuk info paket di tabel
        // 'transactions' untuk riwayat di modal
        $members = $query->with(['membershipPackage', 'transactions', 'bookings'])
                         ->latest()
                         ->paginate(15);
        
        return view('admin.members.index', compact('members'));
    }
    
    public function show($id)
    {
        // Method ini mungkin tidak terpakai jika kita pakai modal,
        // tapi kita biarkan saja untuk referensi
        $member = User::with('transactions')->findOrFail($id);
        return view('admin.members.show', compact('member'));
    }
    
    public function destroy($id)
    {
        $member = User::withCount('bookings')->findOrFail($id);

        if ($member->bookings_count > 0) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Member tidak dapat dihapus karena masih memiliki booking.');
        }
        
        $member->delete();
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil dihapus');
    }
}
