<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'experience' => 'required|integer|min:0',
            'clients' => 'nullable|string',
            'certifications' => 'nullable|string',
            'bio' => 'nullable|string',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format file tidak valid. Hanya file gambar dengan format png atau jpg yang diperbolehkan.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('trainers', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $validated['certifications'] = !empty($validated['certifications'])
            ? array_map('trim', explode(',', $validated['certifications']))
            : [];

        Trainer::create($validated);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer baru berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);

        // Set form_type and trainer_id for error handling before validation
        $request->merge([
            'form_type' => 'edit',
            'trainer_id' => $trainer->id,
        ]);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'experience' => 'required|integer|min:0',
            'clients' => 'nullable|string',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format file tidak valid. Hanya file gambar dengan format png atau jpg yang diperbolehkan.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? 1 : 0;
        
        // Ganti gambar hanya jika ada file baru
        if ($request->hasFile('image')) {
            if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
                Storage::disk('public')->delete($trainer->image);
            }
            
            $path = $request->file('image')->store('trainers', 'public');
            $validatedData['image'] = $path;
        }

        $trainer->update($validatedData);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer berhasil diperbarui');
    }

    public function destroy($id)
    {
        $trainer = Trainer::withCount('classSchedules')->findOrFail($id);

        if ($trainer->class_schedules_count > 0) {
            return redirect()->route('admin.trainers.index')
                ->with('error', 'Trainer tidak dapat dihapus karena masih memiliki jadwal kelas.');
        }

        if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
            Storage::disk('public')->delete($trainer->image);
        }

        $trainer->delete();

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer berhasil dihapus');
    }
}
