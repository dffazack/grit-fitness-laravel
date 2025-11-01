<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // HAPUS __construct() JIKA ADA

    public function index()
    {
        $user = Auth::user();
        // Pastikan view 'member.profile' ada
        return view('member.profile', compact('user')); 
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            // Logika password dipisah agar lebih aman
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->email = $validated['email'];

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    // Tambahkan fungsi untuk update password jika ada
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah']);
            }
            $user->password = Hash::make($validated['password']);
            $user->save();

            return back()->with('success', 'Password berhasil diperbarui');
        }

        return back();
    }
}