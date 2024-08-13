<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services;

use Illuminate\Support\Facades\Http;

class SerperService
{
    public function __construct(
        private readonly string $apiKey
    ) {}

    public function __invoke($searchQuery, string $type = 'search', int $num = 10)
    {

        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->retry(3, 100)->post("https://google.serper.dev/{$type}", [
            'q' => $searchQuery,
            'num' => $num,
        ])->json();

        return $response;
    }
}
