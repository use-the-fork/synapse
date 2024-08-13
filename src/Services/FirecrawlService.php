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

    public function __invoke(string $url, string $extractionPrompt)
    {

        try {

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->throw()->post('https://api.firecrawl.dev/v0/scrape', [
                'url' => $url,
                'extractorOptions' => [
                    'mode' => 'llm-extraction',
                    'extractionPrompt' => "content related to **{$extractionPrompt}** if no relevant content is found simply return `No Relevant Content On Page`",
                    'extractionSchema' => [
                        'type' => 'object',
                        'properties' => [
                            'result' => ['type' => 'string'],
                        ],
                        'required' => [
                            'result',
                        ],
                    ],
                ],
            ])->json();

            return [
                'data' => [
                    'metadata' => $response['data']['metadata'],
                    'linksOnPage' => $response['data']['linksOnPage'],
                    'content' => $response['data']['llm_extraction']['result'],
                ],
            ];

        } catch (Exception $e) {
            return [
                'data' => [
                    'metadata' => [
                        'title' => '500 Page had error.',
                    ],
                    'content' => 'Oops something went wrong and the page could not be scraped.',
                ],
            ];
        }
    }
}
