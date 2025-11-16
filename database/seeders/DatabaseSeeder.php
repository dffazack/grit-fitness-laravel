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
            MembershipPackageSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            TrainerSeeder::class,
            ClassListSeeder::class,
            ClassScheduleSeeder::class,
            FacilitySeeder::class,
            NotificationSeeder::class,
            HomepageContentSeeder::class,
        ]);
    }
}
