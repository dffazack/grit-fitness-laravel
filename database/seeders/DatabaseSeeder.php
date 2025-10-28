<?php

// File: database/seeders/DatabaseSeeder.php

use Illuminate\Database\Seeder;
use Database\Seeders\GritFitnessSeeder; // <-- Import class seeder kustom

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
             GritFitnessSeeder::class, // Panggil class GritFitnessSeeder
        ]);
    }
}
