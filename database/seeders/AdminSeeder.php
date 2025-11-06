<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin; // 1. Panggil Model Admin
use Illuminate\Support\Facades\Hash; // 2. Panggil fitur Hash

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3. Ini adalah kode yang sama persis dengan di tinker!
        // Dia akan mencari admin dengan email ini,
        // jika belum ada, dia akan membuatnya.
        Admin::firstOrCreate(
            ['email' => 'admin@grit.com'], // Cari berdasarkan email ini
            [
                'name' => 'Hazqi Ganteng',
                'password' => Hash::make('admin123') // Isi data ini jika belum ada
            ]
        );
    }
}