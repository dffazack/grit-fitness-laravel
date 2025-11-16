<?php

namespace Database\Factories;

use App\Models\MembershipPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipPackageFactory extends Factory
{
    protected $model = MembershipPackage::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(array_keys(MembershipPackage::TYPES)),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(100000, 500000),
            'duration_months' => $this->faker->randomElement([1, 3, 6, 12]),
            'features' => $this->faker->sentences(3),
            'description' => $this->faker->paragraph,
            'is_active' => true,
            'is_popular' => $this->faker->boolean,
        ];
    }
}
