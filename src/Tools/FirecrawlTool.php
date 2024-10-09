<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Firecrawl\FirecrawlConnector;
use UseTheFork\Synapse\Services\Firecrawl\Requests\FirecrawlRequest;

final class FirecrawlTool extends BaseTool
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('synapse.services.firecrawl.key');

        if (empty($this->apiKey)) {
            throw new MissingApiKeyException('API (FIRECRAWL_API_KEY) key is required.');
        }
    }

    /**
     * Useful for getting the contents of a webpage.
     *
     * @param  string  $url  The full URL to get the contents from.
     * @param  string  $extractionPrompt  A prompt describing what information to extract from the page
     */
    public function handle(
        string $url,
        string $extractionPrompt,
    ): string {

        $firecrawlConnector = new FirecrawlConnector($this->apiKey);
        $firecrawlRequest = new FirecrawlRequest($url, $extractionPrompt);
        $results = $firecrawlConnector->send($firecrawlRequest)->array();


        return $this->parseResults($results);
    }

    private function parseResults(array $results): string
    {
        $snippets = collect();

        if (! empty($results['data']['extract']['metadata']['title'])) {
            $snippets->push("Meta Title: {$results['data']['metadata']['title']}");
        }

        if (! empty($results['data']['extract']['metadata']['description'])) {
            $snippets->push("Meta Description: {$results['data']['extract']['metadata']['description']}");
        }

        if (! empty($results['data']['extract']['result'])) {
            $snippets->push("Content:\n {$results['data']['extract']['result']}");
        }

        if ($snippets->isEmpty()) {
            return 'Could not scrape page.';
        }

        return $snippets->implode("\n");
    }
}
