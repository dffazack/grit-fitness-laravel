<?php

namespace App\Http\Controllers;

use App\Models\HomepageContent;
use App\Models\MembershipPackage;
use App\Models\Notification;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman depan publik.
     */
    public function index()
    {
        // 1. Ambil SEMUA data homepage
        $content = HomepageContent::all()->keyBy('section');

        // 2. Ambil notifikasi
        $notifications = Notification::active()->latest()->get();

        // 3. Siapkan data 'homepage' (untuk hero)
        $heroContent = $content->get('hero') ? $content->get('hero')->content : [];

        // LOGIKA GAMBAR YANG DIPERBAIKI
        $heroImage = asset('images/hero-bg.jpg'); // Default fallback

        if (isset($heroContent['image']) && ! empty($heroContent['image'])) {
            // Cek apakah ini URL eksternal (http/https)
            if (str_starts_with($heroContent['image'], 'http')) {
                $heroImage = $heroContent['image']; // Gunakan langsung
            } else {
                $heroImage = asset($heroContent['image']); // Bungkus dengan asset() untuk file lokal
            }
        }

        $homepage = [
            'title' => $heroContent['title'] ?? 'Transform Your Body, Transform Your Life',
            'subtitle' => $heroContent['subtitle'] ?? 'Bergabunglah dengan GRIT Fitness dan mulai perjalanan transformasi Anda.',
            'image' => $heroImage,
        ];

        // 4. Siapkan data 'stats'
        $stats = $content->get('stats') ? $content->get('stats')->content : array_fill(0, 4, [
            'value' => 'N/A',
            'label' => 'Statistik',
        ]);

        // 5. Siapkan data 'benefits'
        $benefits = $content->get('benefits') ? $content->get('benefits')->content : array_fill(0, 4, [
            'icon' => 'dumbbell',
            'title' => 'Benefit',
            'description' => 'Deskripsi benefit belum diatur.',
        ]);

        // 6. Siapkan data 'testimonials'
        $testimonials = $content->get('testimonials') ? $content->get('testimonials')->content : array_fill(0, 3, [
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
