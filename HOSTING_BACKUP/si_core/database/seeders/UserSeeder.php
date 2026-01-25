<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get some packages to assign to users
        $regular1Month = MembershipPackage::where('type', 'regular')->where('duration_months', 1)->first();
        $regular6Month = MembershipPackage::where('type', 'regular')->where('duration_months', 6)->first();
        $regular12Month = MembershipPackage::where('type', 'regular')->where('duration_months', 12)->first();

        // Admin Account
        User::firstOrCreate(
            ['email' => 'admin@gritfitness.com'], // Kunci unik untuk dicari
            [
                'name' => 'Admin GRIT',
                'password' => Hash::make('password'),
                'phone' => '+62 812 3456 7890',
                'role' => 'admin',
                'membership_status' => 'non-member',
            ]
        );

        // Active Member (Premium -> Regular 6 Months)
        if ($regular6Month) {
            User::firstOrCreate(
                ['email' => 'member@gritfitness.com'], // Kunci unik
                [
                    'name' => 'John Doe',
                    'password' => Hash::make('password'),
                    'phone' => '+62 812 1111 2222',
                    'role' => 'member',
                    'membership_status' => 'active',
                    'membership_package_id' => $regular6Month->id,
                    'membership_expiry' => Carbon::now()->addMonths(6),
                    'joined_date' => Carbon::now(),
                ]
            );
        }

        // Active Member (VIP -> Regular 12 Months)
        if ($regular12Month) {
            User::firstOrCreate(
                ['email' => 'jane@example.com'], // Kunci unik
                [
                    'name' => 'Jane Smith',
                    'password' => Hash::make('password'),
                    'phone' => '+62 813 2222 3333',
                    'role' => 'member',
                    'membership_status' => 'active',
                    'membership_package_id' => $regular12Month->id,
                    'membership_expiry' => Carbon::now()->addMonths(12),
                    'joined_date' => Carbon::now()->subMonths(2),
                ]
            );
        }

        // Pending Member (Basic -> Regular 1 Month)
        if ($regular1Month) {
            User::firstOrCreate(
                ['email' => 'bob@example.com'], // Kunci unik
                [
                    'name' => 'Bob Wilson',
                    'password' => Hash::make('password'),
                    'phone' => '+62 814 3333 4444',
                    'role' => 'guest',
                    'membership_status' => 'pending',
                    'membership_package_id' => $regular1Month->id,
                    'joined_date' => Carbon::now(),
                ]
            );
        }

        // Guest (Registered but not subscribed)
        User::firstOrCreate(
            ['email' => 'alice@example.com'], // Kunci unik
            [
                'name' => 'Alice Brown',
                'password' => Hash::make('password'),
                'phone' => '+62 815 4444 5555',
                'role' => 'guest',
                'membership_status' => 'non-member',
            ]
        );
    }
}
