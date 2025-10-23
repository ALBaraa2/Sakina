<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_id',
        'session_note',
        'session_duration',
        'prescription',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
