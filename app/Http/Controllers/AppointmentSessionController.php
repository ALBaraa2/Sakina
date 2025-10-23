<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointmentSession;
use App\Http\Resources\AppointmentSessionResource;

class AppointmentSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AppointmentSessionResource::collection(AppointmentSession::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
