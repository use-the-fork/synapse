<?php

declare(strict_types=1);

return [
    'integrations' => [
        'openai' => [
            'key' => env('OPENAI_API_KEY'),
            'chat_model' => env('OPENAI_API_CHAT_MODEL', 'gpt-4-turbo'),
            'validate_model' => env('OPENAI_API_VALIDATE_MODEL', 'gpt-4-turbo'),
            'embedding_model' => env('OPENAI_API_EMBEDDING_MODEL', 'text-embedding-ada-002'),
        ],
        'claude' => [
            'key' => env('ANTHROPIC_API_KEY'),
            'chat_model' => env('ANTHROPIC_API_CHAT_MODEL', 'claude-3-5-sonnet-20240620'),
            'validate_model' => env('ANTHROPIC_API_VALIDATE_MODEL', 'claude-3-5-sonnet-20240620'),
        ],
    ],
    'services' => [
        'serp_api' => [
            'key' => env('SERPAPI_API_KEY'),
        ],
        'serper' => [
            'key' => env('SERPER_API_KEY'),
        ],
        'clearbit' => [
            'key' => env('CLEARBIT_API_KEY'),
        ],
        'crunchbase' => [
            'key' => env('CRUNCHBASE_API_KEY'),
        ],
        'firecrawl' => [
            'key' => env('FIRECRAWL_API_KEY'),
        ],
    ],
];
