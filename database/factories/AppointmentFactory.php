<?php

// database/factories/AppointmentFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\Therapist;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition()
    {
        return [
            'patient_id' => User::factory()->state(['role' => 'patient']),
            'therapist_id' => Therapist::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
        ];
    }
}

