<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('member.profile', compact('user')); 
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->email = $validated['email'];

        // Handle the profile photo upload
        if ($request->hasFile('profile_photo')) {
    // Hapus foto lama
    if ($user->profile_photo) {
        Storage::disk('public')->delete($user->profile_photo);
    }

    // Simpan dengan nama folder yang konsisten
    $path = $request->file('profile_photo')->store('profile-photos', 'public');
    $user->profile_photo = $path; // ← pastikan ini sama dengan yang dipakai di blade
}

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }

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
    
    public function updatePhoneQuick(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $user->phone = $validated['phone'];

        $user->save();

        return back()->with('success', 'Phone number updated successfully.');
    }
}