<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Request;

use Illuminate\Support\Facades\Http;

class SerperService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = (string) (config('synapse.services.serper'));
    }

    public function __invoke($searchQuery, $type = 'search', $num = 10)
    {
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
            'Accept' => 'application/json',
        ])->post("https://google.serper.dev/{$type}", [
            'q' => $searchQuery,
            'num' => $num,
        ])->json();

        return $response;
    }
}
