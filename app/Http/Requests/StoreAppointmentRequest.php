<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Appointment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_date' => 'required|date|after:now',
            'therapist_id' => 'required|exists:therapists,id',
        ];
    }

    public function messages()
    {
        return [
            'appointment_date.after' => 'The appointment date must be a future date and time.',
            'therapist_id.exists' => 'The selected therapist is invalid.',
        ];
    }
}
