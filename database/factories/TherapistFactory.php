<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Therapist>
 */
class TherapistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'        => \App\Models\User::factory(), // automatically relates to a user
            'specialization' => $this->faker->randomElement(['Psychologist', 'Counselor', 'Therapist']),
            'bio'            => $this->faker->sentence(),
            'cv'             => null,
        ];
    }
}
