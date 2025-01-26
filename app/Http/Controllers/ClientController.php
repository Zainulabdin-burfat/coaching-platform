<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Get client profile
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'client') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client = $user->client;

        if (!$client) {
            return response()->json(['message' => 'Client profile not found'], 404);
        }

        return response()->json([
            'id' => $client->id,
            'name' => $user->name,
            'email' => $user->email,
            'coach' => $client->coach ? [
                'id' => $client->coach->id,
                'name' => $client->coach->user->name,
                'email' => $client->coach->user->email,
            ] : null,
            'total_sessions' => $client->sessions()->count(),
            'pending_sessions' => $client->sessions()->where('completed', false)->count(),
            'completed_sessions' => $client->sessions()->where('completed', true)->count(),
            'progress' => $client->progress . '%',
        ]);
    }

    /**
     * Client marks a session as completed
     */
    public function markSessionCompleted(Request $request, $sessionId)
    {
        $user = $request->user();

        if ($user->role !== 'client') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $session = Session::where('client_id', $user->client->id)
            ->where('id', $sessionId)
            ->where('completed', false)
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Session not found or already completed'], 404);
        }

        $session->update(['completed' => true]);

        return response()->json(['message' => 'Session marked as completed']);
    }
}
