<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class AIProcessorController extends Controller {
    protected $client;

    public function __construct() {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function processPrompt(Request $request) {
        $request->validate([
            'prompt' => 'required|string|max:1000'
        ]);

        $response = $this->client->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI assistant.'],
                ['role' => 'user', 'content' => $request->input('prompt')]
            ]
        ]);

        return response()->json([
            'prompt' => $request->input('prompt'),
            'response' => $response->choices[0]['message']['content'] ?? 'Error generating response'
        ]);
    }
}
