<?php

namespace Database\Factories;

use App\Models\ClassList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassListFactory extends Factory
{
    protected $model = ClassList::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
