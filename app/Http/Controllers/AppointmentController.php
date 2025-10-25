<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(Appointment::class, 'appointment');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $user = $request->user();
        $query = Appointment::query();

        if ($user->role === 'admin') {
            $query->get();
        } elseif ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        } elseif ($user->role === 'therapist') {
            $therapist = $user->therapist;
            $query->where('therapist_id', $therapist->id);
        }

        if ($from) {
            $query->where('appointment_date', '>=', $from);
        }

        if ($to) {
            $query->where('appointment_date', '<=', $to);
        }

        return AppointmentResource::collection($query->paginate(10));
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

    public function confirmAppointment(Appointment $appointment)
    {
        $this->authorize('confirm', $appointment);

        $appointment->status = 'confirmed';
        $appointment->save();

        return response()->json(['message' => 'Appointment confirmed successfully.'], 200);
    }
}
