<?php
namespace App\Policies;

use App\Models\Therapist;
use App\Models\User;

class TherapistPolicy
{

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Therapist $therapist): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return true;
    }

    public function update(User $user, Therapist $therapist): bool
    {
        return $user->role === 'admin' || $user->id === $therapist->user_id;
    }

    public function delete(User $user, Therapist $therapist): bool
    {
        return $user->role === 'admin' || $user->id === $therapist->user_id;
    }

    public function approve(User $user, Therapist $therapist): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Therapist $therapist): bool
    {
        return false;
    }

    public function forceDelete(User $user, Therapist $therapist): bool
    {
        return false;
    }
}
