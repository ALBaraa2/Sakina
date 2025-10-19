<?php

// database/factories/AppointmentSessionFactory.php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentSessionFactory extends Factory
{
    public function definition()
    {
        return [
            'appointment_id' => Appointment::factory(),
            'session_note' => $this->faker->sentence,
            'session_duration' => $this->faker->numberBetween(30, 90),
            'prescription' => $this->faker->randomElement([null, 'Rest and exercise', 'Medication A']),
        ];
    }
}
