<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Therapist;
use App\Models\Appointment;
use App\Models\AppointmentSession;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create some therapists (with associated user)
        $therapists = Therapist::factory()->count(5)->create();

        // Create some patients
        $patients = User::factory()->count(10)->create();

        // Create appointments
        foreach (range(1, 10) as $i) {
            $appointment = Appointment::factory()->create([
                'patient_id' => $patients->random()->id,
                'therapist_id' => $therapists->random()->id,
            ]);

            // Some appointments may have a session
            if (rand(0, 1)) {
                AppointmentSession::factory()->create([
                    'appointment_id' => $appointment->id,
                ]);
            }
        }
    }
}
