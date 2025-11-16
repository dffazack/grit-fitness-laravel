<?php

namespace Database\Factories;

use App\Models\ClassSchedule;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassScheduleFactory extends Factory
{
    protected $model = ClassSchedule::class;

    public function definition(): array
    {
        return [
            'custom_class_name' => $this->faker->randomElement(ClassSchedule::CLASS_TYPES),
            'day' => $this->faker->randomElement(ClassSchedule::DAYS),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'trainer_id' => Trainer::factory(),
            'max_quota' => $this->faker->numberBetween(10, 20),
            'is_active' => true,
        ];
    }
}
