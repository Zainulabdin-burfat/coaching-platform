<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::post('/register/coach', [AuthController::class, 'registerCoach']); // Public
Route::post('/login', [AuthController::class, 'login']); // Public

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register/client', [AuthController::class, 'registerClient']); // Only Coaches
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Coach can manage clients
    Route::get('/coach/clients', [CoachController::class, 'getClients']);
    Route::put('/coach/client/{clientId}', [CoachController::class, 'updateClient']);
    Route::delete('/coach/client/{clientId}', [CoachController::class, 'deleteClient']);

    // Clients can manage their sessions
    Route::get('/client/profile', [ClientController::class, 'getProfile']);
    Route::post('/client/session/{sessionId}/complete', [ClientController::class, 'markSessionCompleted']);
});
