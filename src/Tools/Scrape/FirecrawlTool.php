<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools\Scrape;

use UseTheFork\Synapse\Contracts\Tool\ScrapeTool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Firecrawl\FirecrawlConnector;
use UseTheFork\Synapse\Services\Firecrawl\Requests\FirecrawlRequest;
use UseTheFork\Synapse\Tools\BaseTool;

final class FirecrawlTool extends BaseTool implements ScrapeTool
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
     *
     */
    public function handle(
        string $url
    ): string {

        $firecrawlConnector = new FirecrawlConnector($this->apiKey);
        $firecrawlRequest = new FirecrawlRequest($url);
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

        if (! empty($results['data']['markdown'])) {
            $snippets->push("Content:\n\n {$results['data']['markdown']}");
        }

        if ($snippets->isEmpty()) {
            return 'Could not scrape page.';
        }

        return $snippets->implode("\n");
    }
}
