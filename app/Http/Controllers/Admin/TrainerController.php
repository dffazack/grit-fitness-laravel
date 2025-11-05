<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- [PENTING] Pastikan ini ada

class TrainerController extends Controller
{

    public function index()
    {
        $trainers = Trainer::latest()->paginate(9);
        return view('admin.trainers.index', compact('trainers'));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers',
            'phone' => 'nullable|string',
            'experience' => 'required|integer|min:0',
            'clients' => 'nullable|string',
            'certifications' => 'nullable|string',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trainers', 'public');
        }
        
        // Set 'is_active'
        $validated['is_active'] = $request->has('is_active');
        
        // Ubah string sertifikasi menjadi array
        if (!empty($validated['certifications'])) {
            $validated['certifications'] = array_map('trim', explode(',', $validated['certifications']));
        } else {
            $validated['certifications'] = []; // Pastikan jadi array kosong
        }
        
        Trainer::create($validated);
        
        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer baru berhasil ditambahkan');
    }

    
    public function show($id)
    {
        // Method ini tidak kita gunakan, tapi tidak apa-apa
    }

    
    public function edit($id)
    {
        // Method ini tidak kita gunakan, tapi tidak apa-apa
    }

    
    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);
        
        // --- [LOGIKA BARU DI SINI] ---
        
        // 1. Validasi semua data TULISAN
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,'.$id,
            'phone' => 'nullable|string',
            'experience' => 'required|integer|min:0',
            'clients' => 'nullable|string',
            'certifications' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);
        
        // 2. Handle checkbox
        $validatedData['is_active'] = $request->has('is_active');
        
        // 3. Handle sertifikasi (string to array)
        if (!empty($validatedData['certifications'])) {
            $validatedData['certifications'] = array_map('trim', explode(',', $validatedData['certifications']));
        } else {
            $validatedData['certifications'] = $trainer->certifications ?? []; // Biarkan yang lama jika kosong
        }

        // 4. Handle upload gambar HANYA JIKA ADA file baru
        if ($request->hasFile('image')) {
            // 4a. Validasi filenya secara terpisah
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
            
            // 4b. Hapus gambar lama (jika ada)
            if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                Storage::disk('public')->delete($trainer->image);
            }
            
            // 4c. Simpan gambar baru dan tambahkan path-nya ke data
            $validatedData['image'] = $request->file('image')->store('trainers', 'public');
        }
        
        // 5. Update trainer dengan semua data yang sudah disiapkan
        // Jika tidak ada file 'image' baru, data 'image' lama tidak akan tersentuh
        $trainer->update($validatedData);
        
        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer berhasil diperbarui');
    }

    
    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);
        
        // Hapus gambar dari storage sebelum hapus data
        if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
            Storage::disk('public')->delete($trainer->image);
        }
        
        $trainer->delete();
        
        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer berhasil dihapus');
    }
}

