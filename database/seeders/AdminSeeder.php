<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@gritfitness.com'], // Email dalam satu baris
            [
                'name' => 'Hazqi Ganteng',
                'password' => Hash::make('admin123')
            ]
        );
    }
}
