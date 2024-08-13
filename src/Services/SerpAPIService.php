<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class SerpAPIService
{
    public function __construct(
        private readonly string $apiKey
    ) {}

    public function __invoke($searchQuery, int $num = 10, string $endpoint = 'https://serpapi.com/search')
    {
        $params = Arr::query([
            'api_key' => $this->apiKey,
            'output' => 'json',
            'q' => $searchQuery,
            'num' => $num,
        ]);
        $response = Http::retry(3, 250)->get("{$endpoint}?{$params}")->json();

        return $response;
    }
}
