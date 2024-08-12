<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class FirecrawlService
{
    public function __construct(
        private readonly string $apiKey
    ) {}

    public function __invoke($url)
    {

      try {

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->throw()->post('https://api.firecrawl.dev/v0/scrape', [
            'pageOptions' => [
                'onlyMainContent' => true,
            ],
            'url' => $url,
        ])->json();

        return $response;

      } catch (Exception $e){
        return [
          'data' => [
            'metadata' => [
              'title' => '500 Page had error.'
            ],
            'content' => 'Oops something went wrong and the page could not be scraped.'
          ]
        ];
      }
    }
}
