<?php

namespace Database\Factories;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerFactory extends Factory
{
    protected $model = Trainer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'specialization' => 'Yoga',
            'experience' => '5+ Tahun',
            'clients' => '100+',
            'certifications' => json_encode(['Certified Yoga Instructor']),
            'bio' => $this->faker->paragraph,
            'is_active' => true,
        ];
    }
}
