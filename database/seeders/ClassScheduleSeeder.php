<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use App\Models\ClassList; // <-- PENTING
use Carbon\Carbon;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari ClassList yang sudah Anda seed di ClassListSeeder
        $yoga = ClassList::where('name', 'Yoga')->first();
        $hiit = ClassList::where('name', 'HIIT')->first();
        $strength = ClassList::where('name', 'Strength')->first();
        $boxing = ClassList::where('name', 'Boxing')->first();
        $pilates = ClassList::where('name', 'Pilates')->first();        if (!$yoga) {
            $yoga = ClassList::factory()->create(['name' => 'Yoga']);
        }
        if (!$hiit) {
            $hiit = ClassList::factory()->create(['name' => 'HIIT']);
        }
        if (!$strength) {
            $strength = ClassList::factory()->create(['name' => 'Strength']);
        }
        if (!$boxing) {
            $boxing = ClassList::factory()->create(['name' => 'Boxing']);
        }
        if (!$pilates) {
            $pilates = ClassList::factory()->create(['name' => 'Pilates']);
        }
        $schedules = [
            // Senin
            [
                'class_list_id' => $yoga->id,
                'custom_class_name' => 'Morning Yoga Flow',
                'day' => 'Senin',
                'start_time' => '06:00',
                'end_time' => '07:00',
                'trainer_id' => 2,
                'max_quota' => 20,
                'quota' => 12,
                'type' => 'Yoga',
                'description' => 'Mulai hari dengan yoga flow yang menenangkan',
                'is_active' => true,
            ],
            [
                'class_list_id' => $hiit->id,
                'custom_class_name' => 'HIIT Bootcamp',
                'day' => 'Senin',
                'start_time' => '18:00',
                'end_time' => '19:00',
                'trainer_id' => 3,
                'max_quota' => 15,
                'quota' => 15,
                'type' => 'HIIT',
                'description' => 'Latihan intensif untuk membakar kalori maksimal',
                'is_active' => true,
            ],
            
            // Selasa
            [
                'class_list_id' => $strength->id,
                'custom_class_name' => 'Strength Training',
                'day' => 'Selasa',
                'start_time' => '07:00',
                'end_time' => '08:00',
                'trainer_id' => 1,
                'max_quota' => 15,
                'quota' => 8,
                'type' => 'Strength',
                'description' => 'Build muscle dengan strength training terprogram',
                'is_active' => true,
            ],
            [
                'class_list_id' => $boxing->id,
                'custom_class_name' => 'Boxing Basics',
                'day' => 'Selasa',
                'start_time' => '19:00',
                'end_time' => '20:00',
                'trainer_id' => 3,
                'max_quota' => 12,
                'quota' => 10,
                'type' => 'Boxing',
                'description' => 'Pelajari teknik boxing dasar sambil cardio',
                'is_active' => true,
            ],
            
            // Rabu
            [
                'class_list_id' => $pilates->id,
                'custom_class_name' => 'Pilates Core',
                'day' => 'Rabu',
                'start_time' => '06:30',
                'end_time' => '07:30',
                'trainer_id' => 2,
                'max_quota' => 18,
                'quota' => 14,
                'type' => 'Pilates',
                'description' => 'Perkuat core dengan pilates mat',
                'is_active' => true,
            ],
            [
                'class_list_id' => $strength->id,
                'custom_class_name' => 'Functional Training',
                'day' => 'Rabu',
                'start_time' => '18:30',
                'end_time' => '19:30',
                'trainer_id' => 4,
                'max_quota' => 16,
                'quota' => 9,
                'type' => 'Strength',
                'description' => 'Latihan fungsional untuk performa optimal',
                'is_active' => true,
            ],
            
            // Kamis
            [
                'class_list_id' => $hiit->id, 
                'custom_class_name' => 'Cardio Blast',
                'day' => 'Kamis',
                'start_time' => '07:00',
                'end_time' => '08:00',
                'trainer_id' => 3,
                'max_quota' => 20,
                'quota' => 16,
                'type' => 'Cardio',
                'description' => 'High-energy cardio untuk stamina',
                'is_active' => true,
            ],
            
            // Jumat
            [
                'class_list_id' => $yoga->id,
                'custom_class_name' => 'Power Yoga',
                'day' => 'Jumat',
                'start_time' => '06:00',
                'end_time' => '07:00',
                'trainer_id' => 2,
                'max_quota' => 20,
                'quota' => 11,
                'type' => 'Yoga',
                'description' => 'Dynamic yoga untuk kekuatan dan fleksibilitas',
                'is_active' => true,
            ],
            [
                'class_list_id' => $hiit->id,
                'custom_class_name' => 'CrossFit Fundamentals',
                'day' => 'Jumat',
                'start_time' => '18:00',
                'end_time' => '19:00',
                'trainer_id' => 1,
                'max_quota' => 12,
                'quota' => 12,
                'type' => 'HIIT',
                'description' => 'Dasar-dasar crossfit untuk pemula',
                'is_active' => true,
            ],
            
            // Sabtu
            [
                'class_list_id' => $strength->id,
                'custom_class_name' => 'Weekend Warriors',
                'day' => 'Sabtu',
                'start_time' => '08:00',
                'end_time' => '09:00',
                'trainer_id' => 1,
                'max_quota' => 15,
                'quota' => 13,
                'type' => 'Strength',
                'description' => 'Full body workout untuk weekend',
                'is_active' => true,
            ],
            [
                'class_list_id' => $boxing->id,
                'custom_class_name' => 'Boxing & Cardio',
                'day' => 'Sabtu',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'trainer_id' => 3,
                'max_quota' => 15,
                'quota' => 7,
                'type' => 'Boxing',
                'description' => 'Kombinasi boxing dan cardio intensif',
                'is_active' => true,
            ],
            
            // Minggu
            [
                'class_list_id' => $yoga->id,
                'custom_class_name' => 'Restorative Yoga',
                'day' => 'Minggu',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'trainer_id' => 2,
                'max_quota' => 25,
                'quota' => 18,
                'type' => 'Yoga',
                'description' => 'Yoga pemulihan untuk relaksasi',
                'is_active' => true,
            ],
        ];

        // Hapus data lama untuk menghindari duplikat
        ClassSchedule::query()->delete(); 
        
        foreach ($schedules as $schedule) {
            ClassSchedule::create($schedule);
        }
    }
}