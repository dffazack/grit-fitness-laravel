<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Admin GRIT',
            'email' => 'admin@gritfitness.com',
            'password' => Hash::make('password'),
            'phone' => '+62 812 3456 7890',
            'role' => 'admin',
            'membership_status' => 'non-member',
        ]);

        // Active Member (Premium)
        User::create([
            'name' => 'John Doe',
            'email' => 'member@gritfitness.com',
            'password' => Hash::make('password'),
            'phone' => '+62 812 1111 2222',
            'role' => 'member',
            'membership_status' => 'active',
            'membership_package' => 'premium',
            'membership_expiry' => Carbon::now()->addMonths(12),
            'joined_date' => Carbon::now(),
        ]);

        // Active Member (VIP)
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'phone' => '+62 813 2222 3333',
            'role' => 'member',
            'membership_status' => 'active',
            'membership_package' => 'vip',
            'membership_expiry' => Carbon::now()->addMonths(12),
            'joined_date' => Carbon::now()->subMonths(2),
        ]);

        // Pending Member
        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'phone' => '+62 814 3333 4444',
            'role' => 'guest',
            'membership_status' => 'pending',
            'membership_package' => 'basic',
            'joined_date' => Carbon::now(),
        ]);

        // Guest (Registered but not subscribed)
        User::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
            'phone' => '+62 815 4444 5555',
            'role' => 'guest',
            'membership_status' => 'non-member',
        ]);
    }
}