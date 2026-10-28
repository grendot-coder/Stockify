<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | Provider utama: 'groq' (Cloud API super cepat) atau 'ollama' (Local fallback)
    | Model utama: 'qwen/qwen3.8-27b' via Groq Vision API
    |
    */

    'provider' => env('AI_PROVIDER', 'groq'),

    'groq' => [
        'api_key' => env('GROQ_API_KEY', ''),
        'model' => env('GROQ_MODEL', 'qwen/qwen3.8-27b'),
        'endpoint' => env('GROQ_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions'),
        'timeout' => (int) env('GROQ_TIMEOUT', 30),
        'temperature' => (float) env('GROQ_TEMPERATURE', 0.1),
    ],

    'ollama' => [
        'url' => env('OLLAMA_URL', 'http://localhost:11434'),
        'vl_model' => env('OLLAMA_VL_MODEL', 'qwen3-vl:2b-instruct'),
        'timeout' => (int) env('OLLAMA_TIMEOUT', 180),
        'temperature' => (float) env('OLLAMA_TEMPERATURE', 0.1),
    ],

    // Backward compatibility aliases
    'ollama_url' => env('OLLAMA_URL', 'http://localhost:11434'),
    'vl_model' => env('OLLAMA_VL_MODEL', 'qwen3-vl:2b-instruct'),
    'timeout' => env('OLLAMA_TIMEOUT', 180),
    'temperature' => env('OLLAMA_TEMPERATURE', 0.1),
];
