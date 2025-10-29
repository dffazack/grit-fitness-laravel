<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPackage;

class MembershipPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Basic Package
        MembershipPackage::create([
            'type' => 'basic',
            'name' => 'Basic Membership',
            'price' => 2500000,
            'duration_months' => 12,
            'features' => [
                'Akses gym area',
                'Akses kelas grup (max 8 kelas/bulan)',
                'Membership card',
                'Loker pribadi',
            ],
            'description' => 'Paket dasar untuk pemula yang ingin memulai perjalanan fitness',
            'is_active' => true,
        ]);

        // Premium Package
        MembershipPackage::create([
            'type' => 'premium',
            'name' => 'Premium Membership',
            'price' => 4500000,
            'duration_months' => 12,
            'features' => [
                'Semua fasilitas Basic',
                'Unlimited akses kelas grup',
                '4 sesi personal training',
                'Akses sauna & steam room',
                'Free 2 guest passes',
                'Konsultasi nutrisi',
            ],
            'description' => 'Paket lengkap untuk hasil maksimal dengan bimbingan profesional',
            'is_active' => true,
        ]);

        // VIP Package
        MembershipPackage::create([
            'type' => 'vip',
            'name' => 'VIP Membership',
            'price' => 6500000,
            'duration_months' => 12,
            'features' => [
                'Semua fasilitas Premium',
                '12 sesi personal training',
                'Nutrition consultation (monthly)',
                'Body composition analysis',
                'Priority class booking',
                'Free towel service',
                'Complimentary drinks',
                'Access to VIP lounge',
            ],
            'description' => 'Paket eksklusif dengan layanan premium dan fasilitas terlengkap',
            'is_active' => true,
        ]);
    }
}