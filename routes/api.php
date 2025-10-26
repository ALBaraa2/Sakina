<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API works!']);
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);
Route::apiResource('therapists', TherapistController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('appointment-sessions', AppointmentSessionController::class);

Route::patch('/therapists/{therapist}/approve', [TherapistController::class, 'approveTherapist'])->middleware(['auth:sanctum', 'can:approve,therapist']);
Route::delete('/appointment-sessions/{id}/hardDelete', [AppointmentSessionController::class, 'hardDelete']);
Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirmAppointment']);
Route::patch('/appointments/{appointment}/reschedule', [AppointmentController::class, 'rescheduleAppointment']);
