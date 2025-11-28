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
        $members = $query->with(['membership', 'transactions'])
                         ->latest()
                         ->paginate(15);
        
        return view('admin.members.index', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil diperbarui');
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
        $member = User::findOrFail($id);

        // Mencegah penghapusan akun dengan role 'admin'
        if ($member->isAdmin()) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Anda tidak dapat menghapus akun dengan role Admin.');
        }

        $member->delete();
        
        return redirect()->route('admin.members.index')
            ->with('success', 'Member berhasil dihapus');
    }
}
