<?php

return [


    'api_key' => env('OPENAI_API_KEY'),

    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),

    'api_url' => 'https://api.openai.com/v1/chat/completions',

    'cache_ttl' => env('OPENAI_CACHE_TTL', 3600), // 1 hour default

    'max_tokens' => 2000, // Increased for full JSON responses

    'temperature' => 0.3, // Lower for more consistent JSON

    // Use mock responses when API is unavailable
    'use_mock' => env('OPENAI_USE_MOCK', false),
];
