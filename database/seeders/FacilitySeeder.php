<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Cardio Zone',
                'description' => 'Treadmill terbaru, Sepeda statis, Elliptical trainer, Rowing machine',
                'image' => 'cardio-zone.jpg',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Strength Training',
                'description' => 'Free weights lengkap, Smith machine, Cable machine, Plate loaded equipment',
                'image' => 'strength-training.jpg',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Group Class Studio',
                'description' => 'Zumba, Yoga, Pilates, Body combat, Spinning class',
                'image' => 'group-class.jpg',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Locker & Shower',
                'description' => 'Loker pribadi, Shower air hangat, Sauna, Ruang ganti luas, Handuk bersih',
                'image' => 'locker-room.jpg',
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}