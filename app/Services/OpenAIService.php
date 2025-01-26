<?php

namespace App\Services;

use OpenAI;

class OpenAIService {
    protected $client;

    public function __construct() {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function generateResponse(string $prompt) {
        $response = $this->client->chat()->create([
            'model' => env('OPENAI_MODEL', 'gpt-4'),
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI assistant.'],
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        return $response->choices[0]['message']['content'] ?? 'Error generating response';
    }
}
