<?php

declare(strict_types=1);

return [
    'integrations' => [
        'openai' => [
            'key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_API_MODEL', 'gpt-4-turbo'),
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
