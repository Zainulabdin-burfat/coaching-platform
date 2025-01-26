<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Coach;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a coach
     */
    public function registerCoach(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);

        // Create User (Coach)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'coach',
        ]);

        // Create Coach Profile
        Coach::create([
            'user_id' => $user->id,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Coach registered successfully!',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Register a client (Only Coach Can Call This)
     */
    public function registerClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $coach = $request->user()->coach;

        if (!$coach) {
            return response()->json(['message' => 'Only coaches can register clients'], 403);
        }

        // Create User (Client)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        // Assign Client to Coach
        Client::create([
            'user_id' => $user->id,
            'coach_id' => $coach->id,
            'progress' => 0,
        ]);

        return response()->json([
            'message' => 'Client registered successfully!',
            'user' => $user,
        ], 201);
    }

    /**
     * Login user and return token with role-based data
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'token' => $token,
        ];

        if ($user->role === 'coach') {
            $coach = $user->coach;
            $data['coach'] = [
                'id' => $coach->id,
                'specialization' => $coach->specialization,
                'bio' => $coach->bio,
                'total_clients' => $coach->clients()->count(),
                'clients' => $coach->clients()->with('user')->get()->map(function ($client) {
                    return [
                        'id' => $client->id,
                        'name' => $client->user->name,
                        'email' => $client->user->email,
                        'total_sessions' => $client->sessions()->count(),
                        'pending_sessions' => $client->sessions()->where('completed', false)->count(),
                        'completed_sessions' => $client->sessions()->where('completed', true)->count(),
                    ];
                }),
            ];
        } elseif ($user->role === 'client') {
            $client = $user->client;
            $data['client'] = [
                'id' => $client->id,
                'coach' => $client->coach ? [
                    'id' => $client->coach->id,
                    'name' => $client->coach->user->name,
                    'email' => $client->coach->user->email,
                ] : null,
                'total_sessions' => $client->sessions()->count(),
                'pending_sessions' => $client->sessions()->where('completed', false)->count(),
                'completed_sessions' => $client->sessions()->where('completed', true)->count(),
                'progress' => $client->progress . '%',
            ];
        }

        return response()->json([
            'message' => 'Login successful!',
            'user' => $data,
        ]);
    }
}
