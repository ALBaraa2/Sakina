<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentSessionResource extends JsonResource
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
            'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
            'session_note' => $this->session_note,
            'session_duration' => $this->session_duration,
            'prescription' => $this->prescription,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
