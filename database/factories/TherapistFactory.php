<?php

// database/factories/TherapistFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TherapistFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory()->therapist(),
            'specialization' => $this->faker->randomElement(['CBT', 'Psychiatry', 'Family Therapy']),
            'bio' => $this->faker->paragraph,
            'cv' => null, // assume null for testing
        ];
    }
}
