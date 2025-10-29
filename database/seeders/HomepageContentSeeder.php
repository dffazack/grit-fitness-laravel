<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageContent;

class HomepageContentSeeder extends Seeder
{
    public function run(): void
    {
        HomepageContent::create([
            'section' => 'hero',
            'content' => [
                'hero_title' => 'Transform Your Body, Elevate Your Mind',
                'hero_subtitle' => 'Join GRIT Fitness dan mulai perjalanan fitness Anda bersama trainer profesional dan fasilitas terlengkap di kota.',
                'hero_cta' => 'Mulai Sekarang',
                'hero_image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1920',
            ],
            'is_active' => true,
        ]);
    }
}