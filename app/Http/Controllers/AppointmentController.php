<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointmentRequest;
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

        if ($request->has('status')) {
            if ($request->query('status') === 'canceled') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->query('status'));
            }
        }

        return AppointmentResource::collection($query->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $validated = $request->validated();

        $validated['patient_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        $date   = \Carbon\Carbon::parse($validated['appointment_date']);
        $hour   = $date->format('H');
        $minute = $date->format('i');

        $exists = \App\Models\Appointment::where('therapist_id', $validated['therapist_id'])
            ->whereRaw('EXTRACT(HOUR FROM appointment_date) = ?', [$hour])
            ->whereRaw('EXTRACT(MINUTE FROM appointment_date) = ?', [$minute])
            ->whereDate('appointment_date', $date->toDateString())
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'There is already an appointment at this time for this therapist, please choose another time.'], 422);
        }

        $appointment = Appointment::create($validated);

        return new AppointmentResource($appointment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load('patient', 'therapist.user', 'session');

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

    public function rescheduleAppointment(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'appointment_date' => 'required|date',
        ]);

        $appointment->appointment_date = $validated['appointment_date'];
        $appointment->status = 'rescheduled';
        $appointment->save();

        return response()->json(['message' => 'Appointment rescheduled successfully.'], 200);
    }
}
