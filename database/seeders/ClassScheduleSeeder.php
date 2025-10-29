<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use Carbon\Carbon;

class ClassScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            // Senin
            [
                'name' => 'Morning Yoga Flow',
                'day' => 'Senin',
                'start_time' => '06:00',
                'end_time' => '07:00',
                'trainer_id' => 2, // Sarah Williams
                'max_quota' => 20,
                'quota' => 12,
                'type' => 'Yoga',
                'description' => 'Mulai hari dengan yoga flow yang menenangkan',
                'is_active' => true,
            ],
            [
                'name' => 'HIIT Bootcamp',
                'day' => 'Senin',
                'start_time' => '18:00',
                'end_time' => '19:00',
                'trainer_id' => 3, // David Rodriguez
                'max_quota' => 15,
                'quota' => 15,
                'type' => 'HIIT',
                'description' => 'Latihan intensif untuk membakar kalori maksimal',
                'is_active' => true,
            ],
            
            // Selasa
            [
                'name' => 'Strength Training',
                'day' => 'Selasa',
                'start_time' => '07:00',
                'end_time' => '08:00',
                'trainer_id' => 1, // Michael Chen
                'max_quota' => 15,
                'quota' => 8,
                'type' => 'Strength',
                'description' => 'Build muscle dengan strength training terprogram',
                'is_active' => true,
            ],
            [
                'name' => 'Boxing Basics',
                'day' => 'Selasa',
                'start_time' => '19:00',
                'end_time' => '20:00',
                'trainer_id' => 3, // David Rodriguez
                'max_quota' => 12,
                'quota' => 10,
                'type' => 'Boxing',
                'description' => 'Pelajari teknik boxing dasar sambil cardio',
                'is_active' => true,
            ],
            
            // Rabu
            [
                'name' => 'Pilates Core',
                'day' => 'Rabu',
                'start_time' => '06:30',
                'end_time' => '07:30',
                'trainer_id' => 2, // Sarah Williams
                'max_quota' => 18,
                'quota' => 14,
                'type' => 'Pilates',
                'description' => 'Perkuat core dengan pilates mat',
                'is_active' => true,
            ],
            [
                'name' => 'Functional Training',
                'day' => 'Rabu',
                'start_time' => '18:30',
                'end_time' => '19:30',
                'trainer_id' => 4, // Lisa Anderson
                'max_quota' => 16,
                'quota' => 9,
                'type' => 'Strength',
                'description' => 'Latihan fungsional untuk performa optimal',
                'is_active' => true,
            ],
            
            // Kamis
            [
                'name' => 'Cardio Blast',
                'day' => 'Kamis',
                'start_time' => '07:00',
                'end_time' => '08:00',
                'trainer_id' => 3, // David Rodriguez
                'max_quota' => 20,
                'quota' => 16,
                'type' => 'Cardio',
                'description' => 'High-energy cardio untuk stamina',
                'is_active' => true,
            ],
            
            // Jumat
            [
                'name' => 'Power Yoga',
                'day' => 'Jumat',
                'start_time' => '06:00',
                'end_time' => '07:00',
                'trainer_id' => 2, // Sarah Williams
                'max_quota' => 20,
                'quota' => 11,
                'type' => 'Yoga',
                'description' => 'Dynamic yoga untuk kekuatan dan fleksibilitas',
                'is_active' => true,
            ],
            [
                'name' => 'CrossFit Fundamentals',
                'day' => 'Jumat',
                'start_time' => '18:00',
                'end_time' => '19:00',
                'trainer_id' => 1, // Michael Chen
                'max_quota' => 12,
                'quota' => 12,
                'type' => 'HIIT',
                'description' => 'Dasar-dasar crossfit untuk pemula',
                'is_active' => true,
            ],
            
            // Sabtu
            [
                'name' => 'Weekend Warriors',
                'day' => 'Sabtu',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'trainer_id' => 1, // Michael Chen
                'max_quota' => 15,
                'quota' => 13,
                'type' => 'Strength',
                'description' => 'Full body workout untuk weekend',
                'is_active' => true,
            ],
            [
                'name' => 'Boxing & Cardio',
                'day' => 'Sabtu',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'trainer_id' => 3, // David Rodriguez
                'max_quota' => 15,
                'quota' => 7,
                'type' => 'Boxing',
                'description' => 'Kombinasi boxing dan cardio intensif',
                'is_active' => true,
            ],
            
            // Minggu
            [
                'name' => 'Restorative Yoga',
                'day' => 'Minggu',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'trainer_id' => 2, // Sarah Williams
                'max_quota' => 25,
                'quota' => 18,
                'type' => 'Yoga',
                'description' => 'Yoga pemulihan untuk relaksasi',
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            ClassSchedule::create($schedule);
        }
    }
}
