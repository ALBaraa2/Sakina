<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Appointment::class, 'appointment');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();

        if ($user->role === 'admin') {
            $appointments = AppointmentResource::collection(Appointment::paginate(10));
        } elseif ($user->role === 'patient') {
            $appointments = AppointmentResource::collection(Appointment::where('patient_id', $user->id)->paginate(10));
        } elseif ($user->role === 'therapist') {
            $therapist = $user->therapist;
            $appointments = AppointmentResource::collection(Appointment::where('therapist_id', $therapist->id)->paginate(10));
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $appointments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'therapist_id' => 'required|exists:therapists,id',
            'appointment_date' => 'required|date',
        ]);

        $validated['patient_id'] = $request->user()->id;
        $validated['status'] = 'pending';
        $appointment = Appointment::create($validated);

        return new AppointmentResource($appointment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->status = 'canceled';
        $appointment->save();
        $appointment->delete();

        return response()->json(['message' => 'Appointment canceled successfully.'], 200);
    }
}
