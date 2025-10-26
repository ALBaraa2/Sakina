<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => new AuthResource($this->patient),
            'therapist' => new AuthResource($this->therapist->user),
            'appointment_date' => $this->appointment_date,
            'status' => $this->status,
            'sessions_note' => $this->session->session_note ?? null,
            'session_duration' => $this->session->session_duration ?? null,
            'prescription' => $this->session->prescription ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
