<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MembershipPackageSeeder::class,
            TrainerSeeder::class,
            ClassScheduleSeeder::class,
            FacilitySeeder::class,
            NotificationSeeder::class,
            HomepageContentSeeder::class,
        ]);
    }
}
