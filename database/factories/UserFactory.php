<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // or Hash::make
            'role' => 'patient', // default, يمكن تغييره لاحقاً
            'phone' => $this->faker->phoneNumber,
            'is_verified' => true,
            'photo' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function therapist()
    {
        return $this->state(fn () => ['role' => 'therapist']);
    }

    public function admin()
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
