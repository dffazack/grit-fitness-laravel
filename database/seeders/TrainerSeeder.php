<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        Trainer::create([
            'name' => 'Michael Chen',
            'specialization' => 'Strength & Conditioning',
            'experience' => '8+ Tahun',
            'clients' => '200+',
            'certifications' => [
                'NSCA-CPT',
                'ACE Certified',
                'CrossFit Level 2',
            ],
            'bio' => 'Spesialis dalam strength training dan conditioning dengan pengalaman melatih atlet profesional dan pemula.',
            'email' => 'michael@gritfitness.com',
            'phone' => '+62 816 1111 2222',
            'is_active' => true,
        ]);

        Trainer::create([
            'name' => 'Sarah Williams',
            'specialization' => 'Yoga & Pilates',
            'experience' => '6+ Tahun',
            'clients' => '150+',
            'certifications' => [
                'RYT-500',
                'Pilates Mat Certified',
                'Prenatal Yoga Certified',
            ],
            'bio' => 'Instruktur yoga dan pilates berpengalaman yang fokus pada mind-body connection dan flexibility.',
            'email' => 'sarah@gritfitness.com',
            'phone' => '+62 817 2222 3333',
            'is_active' => true,
        ]);

        Trainer::create([
            'name' => 'David Rodriguez',
            'specialization' => 'HIIT & Boxing',
            'experience' => '7+ Tahun',
            'clients' => '180+',
            'certifications' => [
                'NASM-PES',
                'Boxing Coach Level 1',
                'HIIT Specialist',
            ],
            'bio' => 'Pelatih energik yang spesialis dalam high-intensity training dan boxing untuk fat loss dan endurance.',
            'email' => 'david@gritfitness.com',
            'phone' => '+62 818 3333 4444',
            'is_active' => true,
        ]);

        Trainer::create([
            'name' => 'Lisa Anderson',
            'specialization' => 'Functional Training',
            'experience' => '5+ Tahun',
            'clients' => '120+',
            'certifications' => [
                'ACE Certified',
                'TRX Certified',
                'Functional Movement Screen',
            ],
            'bio' => 'Fokus pada functional training untuk meningkatkan performa dalam aktivitas sehari-hari.',
            'email' => 'lisa@gritfitness.com',
            'phone' => '+62 819 4444 5555',
            'is_active' => true,
        ]);
    }
}
