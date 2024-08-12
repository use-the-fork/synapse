<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services;

use Illuminate\Support\Facades\Http;

class FirecrawlService
{
    public function __construct(
        private readonly string $apiKey
    ) {}

    public function __invoke($url)
    {

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.firecrawl.dev/v0/scrape', [
            'pageOptions' => [
                'onlyMainContent' => true,
            ],
            'url' => $url,
        ])->json();

        return $response;
    }
}
