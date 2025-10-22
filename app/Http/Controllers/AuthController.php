<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'in:patient,therapist',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string|max:255',
        ]);

        $user = User::create($validated);

        $token = $user->createToken('api-token')->plainTextToken;

        return new AuthResource($user)->additional(['token' => $token], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->role == 'therapist' && !$user->is_verified) {
            return response()->json(['message' => 'Account not verified. Please wait for the admin to approve your account and verify it before logging in.'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return new AuthResource($user)->additional(['token' => $token]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); // Revoke all tokens

        return response()->json(['message' => 'Logged out']);
    }
}
