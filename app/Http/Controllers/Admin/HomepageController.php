<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContent; // Pastikan Anda punya model ini
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Menampilkan halaman ringkasan (sesuai desain).
     */
    public function index()
    {
        // Method ini hanya menampilkan view ringkasan
        return view('admin.homepage.index');
    }

    /**
     * Menampilkan halaman form untuk mengedit konten homepage.
     */
    public function edit()
    {
        // 1. Ambil SEMUA data homepage
        $content = HomepageContent::all()->keyBy('section');

        // 2. Siapkan data untuk view, sesuai yang diharapkan
        // Gunakan data dari database, atau sediakan data kosong jika belum ada
        
        $hero = $content->get('hero') ? json_decode($content->get('hero')->content) : (object)[
            'title' => '',
            'subtitle' => '',
            'image' => '',
        ];
        
        $stats = $content->get('stats') ? json_decode($content->get('stats')->content) : array_fill(0, 4, (object)[
            'value' => '',
            'label' => '',
        ]);
        
        $benefits = $content->get('benefits') ? json_decode($content->get('benefits')->content) : array_fill(0, 4, (object)[
            'icon' => 'dumbbell',
            'title' => '',
            'description' => '',
        ]);
        
        $testimonials = $content->get('testimonials') ? json_decode($content->get('testimonials')->content) : array_fill(0, 3, (object)[
            'name' => '',
            'role' => '',
            'text' => '',
            'rating' => 5,
        ]);

        // 3. Panggil view 'edit'
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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'image' => 'required|url',
        ]);

        HomepageContent::updateOrCreate(
            ['section' => 'hero'],
            ['content' => json_encode($data)]
        );

        return redirect()->route('admin.homepage.edit')->with('success', 'Hero section updated successfully!')->withFragment('hero');
    }

    // Method untuk handle update 'stats'
    public function updateStats(Request $request)
    {
        $data = $request->validate([
            'stats' => 'required|array|size:4',
            'stats.*.value' => 'required|string|max:50',
            'stats.*.label' => 'required|string|max:100',
        ]);

        HomepageContent::updateOrCreate(
            ['section' => 'stats'],
            ['content' => json_encode($data['stats'])]
        );

        return redirect()->route('admin.homepage.edit')->with('success', 'Statistics section updated successfully!')->withFragment('stats');
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

        HomepageContent::updateOrCreate(
            ['section' => 'benefits'],
            ['content' => json_encode($data['benefits'])]
        );

        return redirect()->route('admin.homepage.edit')->with('success', 'Benefits section updated successfully!')->withFragment('benefits');
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

        HomepageContent::updateOrCreate(
            ['section' => 'testimonials'],
            ['content' => json_encode($data['testimonials'])]
        );

        return redirect()->route('admin.homepage.edit')->with('success', 'Testimonials section updated successfully!')->withFragment('testimonials');
    }
}
