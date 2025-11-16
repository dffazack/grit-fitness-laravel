<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPackage;

class MembershipPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            // STUDENT PACKAGES
            [
                'type' => 'student',
                'name' => '1 Bulan',
                'price' => 275000,
                'duration_months' => 1,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    '2 kelas grup per bulan'
                ],
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'type' => 'student',
                'name' => '3 Bulan',
                'price' => 742500,
                'duration_months' => 3,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    '2 kelas grup per bulan',
                    'Hemat 10%'
                ],
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'type' => 'student',
                'name' => '6 Bulan',
                'price' => 1402500,
                'duration_months' => 6,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    '4 kelas grup per bulan',
                    'Hemat 15%'
                ],
                'is_active' => true,
                'is_popular' => true, // RECOMMENDED
            ],
            [
                'type' => 'student',
                'name' => '12 Bulan',
                'price' => 2640000,
                'duration_months' => 12,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    'Unlimited kelas grup',
                    'Hemat 20%',
                    'Bonus merchandise'
                ],
                'is_active' => true,
                'is_popular' => false,
            ],

            // REGULAR PACKAGES
            [
                'type' => 'regular',
                'name' => 'basic',
                'price' => 325000,
                'duration_months' => 1,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    '4 kelas grup per bulan',
                    '1 sesi PT gratis'
                ],
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'type' => 'regular',
                'name' => 'premium',
                'price' => 877500,
                'duration_months' => 3,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    'Unlimited kelas grup',
                    '1 sesi PT gratis/bulan',
                    'Hemat 10%'
                ],
                'is_active' => true,
                'is_popular' => false,
            ],
            [
                'type' => 'regular',
                'name' => 'vip',
                'price' => 1657500,
                'duration_months' => 6,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    'Unlimited kelas grup',
                    '2 sesi PT gratis/bulan',
                    'Konsultasi nutrisi',
                    'Hemat 15%'
                ],
                'is_active' => true,
                'is_popular' => true, // RECOMMENDED
            ],
            [
                'type' => 'regular',
                'name' => '12 Bulan',
                'price' => 3120000,
                'duration_months' => 12,
                'features' => [
                    'Akses gym 24/7',
                    'Loker pribadi',
                    'Shower & sauna',
                    'WiFi gratis',
                    'Area cardio & strength',
                    'Unlimited kelas grup',
                    '3 sesi PT gratis/bulan',
                    'Konsultasi nutrisi',
                    'Akses area VIP',
                    'Hemat 20%',
                    'Bonus merchandise'
                ],
                'is_active' => true,
                'is_popular' => false,
            ],
        ];

        foreach ($packages as $package) {
            MembershipPackage::create($package);
        }
    }
}
