<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'therapist_id',
        'appointment_date',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }

    public function session()
    {
        return $this->hasOne(AppointmentSession::class);
    }
}
