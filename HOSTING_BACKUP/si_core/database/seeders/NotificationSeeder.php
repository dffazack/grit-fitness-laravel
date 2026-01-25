<?php

namespace Database\Seeders;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Active Promo
        Notification::create([
            'title' => 'Promo Tahun Baru',
            'message' => 'Dapatkan diskon 20% untuk semua paket membership! Hanya sampai 31 Januari.',
            'type' => 'promo',
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(25),
            'is_active' => true,
        ]);

        // Upcoming Event
        Notification::create([
            'title' => 'Fitness Challenge 2025',
            'message' => 'Daftar sekarang untuk mengikuti Fitness Challenge bulan Februari! Hadiah jutaan rupiah menanti.',
            'type' => 'event',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(30),
            'is_active' => true,
        ]);

        // Announcement
        Notification::create([
            'title' => 'Jam Operasional Libur Nasional',
            'message' => 'GRIT Fitness tetap buka pada hari libur nasional dengan jam operasional khusus 08:00 - 18:00.',
            'type' => 'announcement',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(7),
            'is_active' => true,
        ]);
    }
}
