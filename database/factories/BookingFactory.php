<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\ClassSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'class_schedule_id' => ClassSchedule::factory(),
            'booking_date' => $this->faker->date(),
            'status' => 'confirmed',
        ];
    }
}
