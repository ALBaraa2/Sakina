<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id'      => \App\Models\User::factory(),
            'therapist_id'    => \App\Models\Therapist::factory(),
            'appointment_date'=> $this->faker->dateTimeBetween('now', '+30 days'),
            'status'          => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
        ];
    }
}
