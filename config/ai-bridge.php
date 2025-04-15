<?php

return [
    'default' => env('AI_DEFAULT_DRIVER', 'openai'),
    
    'drivers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'embedding_model' => env('OPENAI_EMBEDDING_MODEL', 'text-embedding-ada-002'),
        ],
        
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-pro'),
        ],
        
        'claude' => [
            'api_key' => env('CLAUDE_API_KEY'),
            'model' => env('CLAUDE_MODEL', 'claude-2'),
        ],

        'deepseek' => [
            'api_key' => env('DEEPSEEK_API_KEY'),
            'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
        ],

        'mistral' => [
            'api_key' => env('MISTRAL_API_KEY'),
            'model' => env('MISTRAL_MODEL', 'mistral-large-latest'),
        ],

        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-2'),
        ],

        'cohere' => [
            'api_key' => env('COHERE_API_KEY'),
            'model' => env('COHERE_MODEL', 'command-a-03-2025'),
            'client_name' => env('COHERE_CLIENT_NAME', 'laravel-ai-gateway'),
        ],
    ],
    
    'fallback_order' => ['openai', 'gemini', 'claude', 'deepseek'],
];