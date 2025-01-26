<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        return response()->json(Session::with('coach.user', 'client.user')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'coach_id' => 'required|exists:coaches,id',
            'client_id' => 'required|exists:clients,id',
            'scheduled_at' => 'required|date',
            'completed' => 'boolean',
        ]);

        $session = Session::create($request->all());
        return response()->json($session, 201);
    }

    public function show($id)
    {
        return response()->json(Session::with('coach.user', 'client.user')->findOrFail($id));
    }
}
