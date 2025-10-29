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
                'name' => 'Modern Gym Equipment',
                'description' => 'Peralatan fitness terbaru dari brand ternama dunia',
                'icon' => 'bi-dumbbell',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Group Class Studio',
                'description' => 'Studio luas dengan sound system premium untuk kelas grup',
                'icon' => 'bi-people',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Sauna & Steam Room',
                'description' => 'Relaksasi otot setelah workout dengan sauna dan steam room',
                'icon' => 'bi-droplet',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Personal Locker',
                'description' => 'Loker pribadi yang aman untuk menyimpan barang berharga',
                'icon' => 'bi-lock',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Changing Room & Shower',
                'description' => 'Ruang ganti dan shower bersih dengan fasilitas lengkap',
                'icon' => 'bi-water',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Free Parking',
                'description' => 'Area parkir luas dan gratis untuk member',
                'icon' => 'bi-car-front',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Cafe & Juice Bar',
                'description' => 'Juice bar dengan menu sehat dan bergizi',
                'icon' => 'bi-cup-straw',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'WiFi & Lounge',
                'description' => 'Area lounge nyaman dengan WiFi gratis',
                'icon' => 'bi-wifi',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
