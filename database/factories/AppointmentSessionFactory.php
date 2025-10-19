<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppointmentSession>
 */
class AppointmentSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appointment_id' => \App\Models\Appointment::factory(),
            'session_note'   => $this->faker->sentence(),
            'session_duration'=> $this->faker->numberBetween(30, 90),
            'prescription'    => $this->faker->randomElement(['None', 'Medication', 'Therapy Exercises']),
        ];
    }
}
