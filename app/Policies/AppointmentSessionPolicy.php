<?php

namespace App\Policies;

use App\Models\AppointmentSession;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentSessionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'patient', 'therapist']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppointmentSession $appointmentSession): bool
    {
        return $user->role === 'admin' || $user->id === $appointmentSession->appointment->patient_id || $user->id === $appointmentSession->appointment->therapist->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'therapist';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppointmentSession $appointmentSession): bool
    {
        return $user->role === 'therapist' && $user->id === $appointmentSession->appointment->therapist->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AppointmentSession $appointmentSession): bool
    {
        return $user->role === 'therapist' && $user->id === $appointmentSession->appointment->therapist->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppointmentSession $appointmentSession): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppointmentSession $appointmentSession): bool
    {
        return $user->role === 'admin';
    }
}
