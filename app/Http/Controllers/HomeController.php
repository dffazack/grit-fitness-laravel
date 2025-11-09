<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\HomepageContent;
use App\Models\Notification;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman depan publik.
     */
    public function index()
    {
        // 1. Ambil SEMUA data homepage
        $content = HomepageContent::all()->keyBy('section');
        
        // 2. Ambil notifikasi (ini dari kode Anda yang lama, sudah benar)
        $notifications = Notification::active()->latest()->get();

        // 3. Siapkan data 'homepage' (untuk hero)
        // Gunakan 'true' untuk mengubah JSON jadi ARRAY, BUKAN object
        $homepage = $content->get('hero') ? json_decode($content->get('hero')->content, true) : [
            'title' => 'Transform Your Body, Transform Your Life',
            'subtitle' => 'Bergabunglah dengan GRIT Fitness dan mulai perjalanan transformasi Anda.',
            'image' => asset('images/hero-bg.jpg'), // Sediakan gambar default
        ];

        // 4. Siapkan data 'stats'
        $stats = $content->get('stats') ? json_decode($content->get('stats')->content, true) : array_fill(0, 4, [
            'value' => 'N/A',
            'label' => 'Statistik',
        ]);
        
        // 5. Siapkan data 'benefits'
        // [PERBAIKAN]: Mengganti 'iconName' menjadi 'icon' agar cocok dengan form
        $benefits = $content->get('benefits') ? json_decode($content->get('benefits')->content, true) : array_fill(0, 4, [
            'icon' => 'dumbbell', // <-- SUDAH DIGANTI
            'title' => 'Benefit',
            'description' => 'Deskripsi benefit belum diatur.',
        ]);
        
        // 6. Siapkan data 'testimonials'
        $testimonials = $content->get('testimonials') ? json_decode($content->get('testimonials')->content, true) : array_fill(0, 3, [
            'name' => 'Member',
            'role' => 'Member GRIT',
            'text' => 'Testimoni belum diatur.',
            'rating' => 5,
        ]);

        // 7. Ambil paket membership populer
        $popularPackages = MembershipPackage::where('is_active', true)
            ->where('is_popular', true)
            ->orderBy('price')
            ->get();

        // 8. Kirim SEMUA data ke view
        return view('home.index', compact(
            'homepage', 
            'notifications',
            'stats',
            'benefits',
            'testimonials',
            'popularPackages'
        ));
    }
}

