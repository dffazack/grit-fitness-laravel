<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContent;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        $homepage = HomepageContent::where('section', 'hero')->first();
        return view('admin.masterdata.homepage', compact('homepage'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string',
            'hero_subtitle' => 'required|string',
            'hero_cta' => 'required|string',
            'hero_image' => 'nullable|url',
        ]);
        
        HomepageContent::updateOrCreate(
            ['section' => 'hero'],
            ['content' => json_encode($validated)]
        );
        
        return back()->with('success', 'Homepage berhasil diperbarui');
    }
}
