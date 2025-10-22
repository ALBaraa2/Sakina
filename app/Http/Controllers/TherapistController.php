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
            'cv'             => 'required|string|url|max:255',
            'specialization' => 'required|string|max:255',
        ]);

        $therapist = DB::transaction(function () use ($validated, $request) {
            // $user = User::create([
            //     'name'     => $validated['name'],
            //     'email'    => $validated['email'],
            //     'password' => bcrypt($validated['password']),
            //     'role'     => 'therapist',
            //     'phone'    => $validated['phone'] ?? null,
            //     'photo'    => $validated['photo'] ?? null,
            //     'is_verified' => false,
            // ]);

            $authController = new AuthController();
            $registrationRequest = new Request([
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'role'     => 'therapist',
                'phone'    => $request->input('phone'),
                'photo'    => $request->input('photo'),
            ]);
            $authResponse = $authController->register($registrationRequest);
            $user = $authResponse->resource;

            $therapistModel = $user->therapist()->create($validated);

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
        $userId = $therapist->user->id;
        $therapist->delete();
        $user = User::find($userId)->delete();

        return response()->json(['message' => 'Therapist and associated user deleted successfully.']);
    }

    public function approveTherapist(Request $request, Therapist $therapist)
    {
        $therapist->user->is_verified = true;
        $therapist->user->save();

        return response()->json(['message' => 'Therapist approved successfully.']);
    }
}
