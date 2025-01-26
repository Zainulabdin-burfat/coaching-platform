<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class OpenAIController extends Controller {
    protected $aiService;

    public function __construct(OpenAIService $aiService) {
        $this->aiService = $aiService;
    }

    public function processPrompt(Request $request) {
        $request->validate([
            'prompt' => 'required|string|max:1000'
        ]);

        $response = $this->aiService->generateResponse($request->input('prompt'));

        return response()->json([
            'prompt' => $request->input('prompt'),
            'response' => $response
        ]);
    }
}
