<?php

namespace App\Http\Controllers;

use App\Http\Resources\TherapistResource;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TherapistController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'store']);
        $this->authorizeResource(Therapist::class, 'therapist');
    }
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

            $authController = new AuthController();
            $authResponse = $authController->register(new Request(array_merge($request->all(), ['role' => 'therapist'])));
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
        User::find($userId)->delete();

        return response()->json(['message' => 'Therapist and associated user deleted successfully.']);
    }

    public function approveTherapist(Request $request, Therapist $therapist)
    {
        $this->authorize('approve', $therapist);

        $therapist->user->is_verified = true;
        $therapist->user->save();

        return response()->json(['message' => 'Therapist approved successfully.']);
    }
}
