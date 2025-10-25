<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointmentSession;
use App\Http\Resources\AppointmentSessionResource;
use Illuminate\Support\Facades\DB;

class AppointmentSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AppointmentSession::query();
        $user = $request->user();

        if ($user->role === 'patient') {
            $query->whereHas('appointment', function ($q) use ($user) {
                $q->where('patient_id', $user->id);
            });
        } elseif ($user->role === 'therapist') {
            $therapist = $user->therapist;
            $query->whereHas('appointment', function ($q) use ($therapist) {
                $q->where('therapist_id', $therapist->id);
            });
        }

        if ($request->has('appointment_id')) {
            $query->where('appointment_id', $request->input('appointment_id'));
        }

        return AppointmentSessionResource::collection($query->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id'   => 'required|exists:appointments,id',
            'session_note'    => 'required|string',
            'session_duration'=> 'required|integer',
            'prescription'    => 'nullable|string',
        ]);

        $appointmentSession = DB::transaction(function () use ($validated) {

            $appointmentSession = AppointmentSession::create($validated);

            $appointmentSession->appointment->status = 'completed';
            $appointmentSession->appointment->save();

            return $appointmentSession;
        });

        return new AppointmentSessionResource($appointmentSession);
    }

    /**
     * Display the specified resource.
     */
    public function show(AppointmentSession $appointmentSession)
    {
        return new AppointmentSessionResource($appointmentSession);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppointmentSession $appointmentSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppointmentSession $appointmentSession)
    {
        $appointmentSession->delete();

        return response()->json(['message' => 'Session soft deleted.'], 200);
    }

    public function hardDelete(string $id)
    {
        $appointmentSession = AppointmentSession::withTrashed()->findOrFail($id);
        $appointmentSession->forceDelete();

        return response()->json(['message' => 'Session permanently deleted'], 200);
    }
}
