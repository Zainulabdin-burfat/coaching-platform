<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /**
     * Get all clients assigned to the authenticated coach
     */
    public function getClients(Request $request)
    {
        $coach = $request->user()->coach;

        if (!$coach) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $clients = $coach->clients()->with('user')->get()->map(function ($client) {
            return [
                'id' => $client->id,
                'name' => $client->user->name,
                'email' => $client->user->email,
                'total_sessions' => $client->sessions()->count(),
                'pending_sessions' => $client->sessions()->where('completed', false)->count(),
                'completed_sessions' => $client->sessions()->where('completed', true)->count(),
                'progress' => $client->progress . '%',
            ];
        });

        return response()->json($clients);
    }

    /**
     * Update client details (Only Coach can update)
     */
    public function updateClient(Request $request, $clientId)
    {
        $coach = $request->user()->coach;

        if (!$coach) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client = Client::where('id', $clientId)->where('coach_id', $coach->id)->first();

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $request->validate([
            'progress' => 'nullable|numeric|min:0|max:100',
        ]);

        $client->update($request->only('progress'));

        return response()->json(['message' => 'Client updated successfully!', 'client' => $client]);
    }

    /**
     * Delete a client (Only Coach can delete)
     */
    public function deleteClient(Request $request, $clientId)
    {
        $coach = $request->user()->coach;

        if (!$coach) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client = Client::where('id', $clientId)->where('coach_id', $coach->id)->first();

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $client->delete();
        User::where('id', $client->user_id)->delete(); // Delete from users table too

        return response()->json(['message' => 'Client deleted successfully']);
    }
}
