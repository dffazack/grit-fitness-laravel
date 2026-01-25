<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPackage; // Pastikan ini benar
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi 'type'

class MembershipPackageController extends Controller
{
    /**
     * Menampilkan halaman index (daftar paket)
     */
    public function index()
    {
        $packages = MembershipPackage::orderBy('price', 'asc')->get();

        // Data ini kita kirim ke modal
        $types = MembershipPackage::TYPES;

        return view('admin.membership.index', compact('packages', 'types'));
    }

    /**
     * Menyimpan paket baru dari modal 'Tambah'.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(array_keys(MembershipPackage::TYPES))],
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'duration_months' => 'required|integer|min:1',
            'features' => 'required|array', // Changed to array
            'features.*' => 'required|string|max:255', // Validate each feature item
            'description' => 'nullable|string',
            'is_active' => 'nullable', // Checkbox
            'is_popular' => 'nullable', // Jika Anda menambahkan kolom ini
        ]);

        // Trim whitespace from each feature
        $validated['features'] = array_map('trim', $validated['features']);

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        MembershipPackage::create($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Paket membership berhasil ditambahkan.');
    }

    /**
     * Mengupdate paket yang ada dari modal 'Edit'.
     */
    public function update(Request $request, $id)
    {
        $package = MembershipPackage::findOrFail($id);

        // Get all existing types from the database and merge with the official types
        $existingTypes = MembershipPackage::distinct()->pluck('type')->toArray();
        $allowedTypes = array_unique(array_merge($existingTypes, array_keys(MembershipPackage::TYPES)));

        $validated = $request->validate([
            'type' => ['required', Rule::in($allowedTypes)],
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'duration_months' => 'required|integer|min:1',
            'features' => 'required|array', // Changed to array
            'features.*' => 'required|string|max:255', // Validate each feature item
            'description' => 'nullable|string',
            'is_active' => 'nullable',
            'is_popular' => 'nullable',
        ]);

        // Trim whitespace from each feature
        $validated['features'] = array_map('trim', $validated['features']);

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');

        $package->update($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Paket membership berhasil diperbarui.');
    }

    /**
     * Menghapus paket membership.
     */
    public function destroy($id)
    {
        $package = MembershipPackage::findOrFail($id);

        // Cek apakah ada member yang sedang pakai paket ini
        $isUsedByUser = \App\Models\User::where('membership_package_id', $id)->exists();
        $isUsedInTransaction = \App\Models\Transaction::where('membership_package_id', $id)->exists();

        if ($isUsedByUser || $isUsedInTransaction) {
            return redirect()->route('admin.memberships.index')
                ->with('error', 'Paket tidak dapat dihapus karena sedang digunakan oleh member atau ada dalam riwayat transaksi. Nonaktifkan paket jika tidak ingin digunakan lagi.');
        }

        $package->delete();

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Paket membership berhasil dihapus.');
    }
}
