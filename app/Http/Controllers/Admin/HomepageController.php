<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\HomepageContent;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Menampilkan halaman ringkasan (sesuai desain).
     */
    public function index()
    {
        return view('admin.homepage.index');
    }

    /**
     * Menampilkan halaman form untuk mengedit konten homepage.
     */
    public function edit()
    {
        $content = HomepageContent::all()->keyBy('section');

        // Hero Data
        $hero = $content->get('hero') ? (object)$content->get('hero')->content : (object)[
            'title' => '',
            'subtitle' => '',
            'image' => '',
        ];
        
        // Stats Data
        $stats = $content->get('stats') ? array_map(fn($item) => (object)$item, $content->get('stats')->content) : array_fill(0, 4, (object)[
            'value' => '',
            'label' => '',
        ]);
        
        // Benefits Data
        $benefits = $content->get('benefits') ? array_map(fn($item) => (object)$item, $content->get('benefits')->content) : array_fill(0, 4, (object)[
            'icon' => 'dumbbell',
            'title' => '',
            'description' => '',
        ]);
        
        // Testimonials Data
        $testimonials = $content->get('testimonials') ? array_map(fn($item) => (object)$item, $content->get('testimonials')->content) : array_fill(0, 3, (object)[
            'name' => '',
            'role' => '',
            'text' => '',
            'rating' => 5,
        ]);

        return view('admin.homepage.edit', compact(
            'hero',
            'stats',
            'benefits',
            'testimonials'
        ));
    }

    // Method untuk handle update 'hero'
    public function updateHero(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', 
        ]);

        // 2. Ambil Data (Gunakan firstOrNew agar aman)
        $homepageContent = HomepageContent::firstOrNew(['section' => 'hero']);
        
        // Ambil konten yang ada sekarang (jika ada)
        $currentContent = $homepageContent->content ?? [];

        // 3. Tentukan Image Path (Logika Prioritas)
        $imagePath = $currentContent['image'] ?? null;

        if ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $imageName = 'hero-' . time() . '.' . $imageFile->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('images/homepage', $imageFile, $imageName);
            $imagePath = 'storage/images/homepage/' . $imageName;
        }

        // 4. Susun Data Final
        $data = [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'image' => $imagePath,
        ];

        // 5. Simpan
        $homepageContent->content = $data;
        $homepageContent->save();

        return redirect()->route('admin.homepage.edit')
            ->with('success', 'Hero section updated successfully!')
            ->withFragment('hero');
    }

    // Method untuk handle update 'stats'
    public function updateStats(Request $request)
    {
        $data = $request->validate([
            'stats' => 'required|array|size:4',
            // Hapus max:50 agar bisa input angka besar
            'stats.*.value' => 'required|numeric', 
            'stats.*.label' => 'required|string|max:100',
        ]);

        // Gunakan firstOrNew agar aman
        $homepageContent = HomepageContent::firstOrNew(['section' => 'stats']);
        $homepageContent->content = $data['stats'];
        $homepageContent->save();

        return redirect()->route('admin.homepage.edit')
            ->with('success', 'Statistics section updated successfully!')
            ->withFragment('stats');
    }

    // Method untuk handle update 'benefits'
    public function updateBenefits(Request $request)
    {
        $data = $request->validate([
            'benefits' => 'required|array|size:4',
            'benefits.*.icon' => 'required|string',
            'benefits.*.title' => 'required|string|max:100',
            'benefits.*.description' => 'required|string',
        ]);

        // Gunakan firstOrNew agar aman
        $homepageContent = HomepageContent::firstOrNew(['section' => 'benefits']);
        $homepageContent->content = $data['benefits'];
        $homepageContent->save();

        return redirect()->route('admin.homepage.edit')
            ->with('success', 'Benefits section updated successfully!')
            ->withFragment('benefits');
    }

    // Method untuk handle update 'testimonials'
    public function updateTestimonials(Request $request)
    {
        $data = $request->validate([
            'testimonials' => 'required|array|size:3',
            'testimonials.*.name' => 'required|string|max:100',
            'testimonials.*.role' => 'required|string|max:100',
            'testimonials.*.text' => 'required|string',
            'testimonials.*.rating' => 'required|integer|min:1|max:5',
        ]);

        // Gunakan firstOrNew agar aman
        $homepageContent = HomepageContent::firstOrNew(['section' => 'testimonials']);
        $homepageContent->content = $data['testimonials'];
        $homepageContent->save();

        return redirect()->route('admin.homepage.edit')
            ->with('success', 'Testimonials section updated successfully!')
            ->withFragment('testimonials');
    }
}