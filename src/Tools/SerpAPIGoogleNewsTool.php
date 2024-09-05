<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\SerpApi\Requests\SerpApiSearchRequest;
use UseTheFork\Synapse\Services\SerpApi\SerpApiConnector;
use UseTheFork\Synapse\Tools\Contracts\Tool;
use UseTheFork\Synapse\Tools\Exceptions\MissingApiKeyException;

#[Description('Search Google News using a query.')]
final class SerpAPIGoogleNewsTool extends BaseTool implements Tool
{
    private string $apiKey;

    public function __construct(?string $apiKey = null)
    {

        if (! empty($apiKey)) {
            $this->apiKey = $apiKey;
        }

        parent::__construct();
    }

    protected function initializeTool(): void
    {
        if (! empty($this->apiKey)) {
            return;
        }

        if (empty($this->apiKey) && ! empty(config('synapse.services.serp_api.key'))) {
            $this->apiKey = config('synapse.services.serp_api.key');

            return;
        }
        throw new MissingApiKeyException('API (SERPAPI_API_KEY) key is required.');
    }

    public function handle(
        #[Description('Parameter defines the query you want to search.')]
        string $query,
    ): string {

        $this->log('Entered', [
            'query' => $query,
        ]);

        $serpApiConnector = new SerpApiConnector($this->apiKey);
        $serpApiSearchRequest = new SerpApiSearchRequest($query, 0, 'google_news');

        $results = $serpApiConnector->send($serpApiSearchRequest)->array();
        $this->log('Finished');

        return $this->parseResults($results);
    }

    private function parseResults($results): string
    {

        $snippets = collect();

        if (! empty($results['news_results'])) {
            $newsResults = Arr::get($results, 'news_results');

            foreach ($newsResults as $value) {
                $result = collect();
                $result->push("```text\n### Title: {$value['title']}");

                if (! empty($value['stories'])) {
                    foreach (Arr::get($value, 'stories', []) as $story) {

                        $result->push("#### Story: {$story['title']}");
                        $result->push("- Date: {$story['date']}");
                        $result->push("- Link: {$story['link']}");
                    }
                }
                if (! empty($value['source'])) {
                    $result->push("- Date: {$value['date']}");
                    $result->push("- Link: {$value['link']}");
                }
                $result->push("```\n");

                $snippets->push($result->implode("\n"));
            }
        }

        if ($snippets->isEmpty()) {
            return 'No good Google News Result found';
        }

        return $snippets->implode("\n");
    }
}
