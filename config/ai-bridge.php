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
    ],
    
    'fallback_order' => ['openai', 'gemini', 'claude'],
];