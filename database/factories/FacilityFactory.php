<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->paragraph,
            'image' => null,
            'is_active' => true,
            'order' => $this->faker->unique()->numberBetween(1, 100),
        ];
    }
}
