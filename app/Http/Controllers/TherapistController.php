<?php

namespace App\Http\Controllers;

use App\Http\Resources\TherapistResource;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TherapistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TherapistResource::collection(Therapist::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',
            'phone'          => 'nullable|string|max:20',
            'photo'          => 'nullable|string|max:255',
            'cv'             => 'nullable|string|max:255',
            'specialization' => 'required|string|max:255',
        ]);

        $therapist = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role'     => 'therapist',
                'phone'    => $validated['phone'] ?? null,
                'photo'    => $validated['photo'] ?? null,
                'is_verified' => false,
            ]);

            $therapistModel = $user->therapist()->create([
                'cv'             => $validated['cv'] ?? null,
                'specialization' => $validated['specialization'],
            ]);

            return $therapistModel;
        });

        return new TherapistResource($therapist);
    }

    /**
     * Display the specified resource.
     */
    public function show(Therapist $therapist)
    {
        return new TherapistResource($therapist);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Therapist $therapist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Therapist $therapist)
    {
        //
    }

    public function verifyTherapist(Request $request, Therapist $therapist)
    {
        $therapist->user->is_verified = true;
        $therapist->user->save();

        return response()->json(['message' => 'Therapist verified successfully.']);
    }
}
