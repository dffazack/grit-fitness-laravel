<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        $trainers = Trainer::latest()->paginate(15);
        return view('admin.masterdata.trainers', compact('trainers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string',
            'experience' => 'required|string',
            'clients' => 'required|string',
            'certifications' => 'required|array',
            'bio' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trainers', 'public');
        }
        
        Trainer::create($validated);
        
        return back()->with('success', 'Trainer berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $trainer = Trainer::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string',
            'experience' => 'required|string',
            'clients' => 'required|string',
            'certifications' => 'required|array',
            'bio' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trainers', 'public');
        }
        
        $trainer->update($validated);
        
        return back()->with('success', 'Trainer berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $trainer = Trainer::findOrFail($id);
        $trainer->delete();
        
        return back()->with('success', 'Trainer berhasil dihapus');
    }
}

