<?php

namespace Database\Factories;

use App\Models\HomepageContent;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomepageContentFactory extends Factory
{
    protected $model = HomepageContent::class;

    public function definition(): array
    {
        return [
            'section' => $this->faker->word,
            'content' => ['title' => $this->faker->sentence],
            'is_active' => true,
        ];
    }
}
