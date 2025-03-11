<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools\Search;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Contracts\Tool\SearchTool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\SerpApi\Requests\SerpApiSearchRequest;
use UseTheFork\Synapse\Services\SerpApi\SerpApiConnector;
use UseTheFork\Synapse\Tools\BaseTool;

final class SerpAPIGoogleSearchTool extends BaseTool implements Tool, SearchTool
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('synapse.services.serp_api.key');

        if (empty($this->apiKey)) {
            throw new MissingApiKeyException('API (SERPAPI_API_KEY) key is required.');
        }
    }

    /**
     * Search Google using a query.
     *
     * @param  string  $query  Parameter defines the query you want to search.
     * @param  int  $numberOfResults  Parameter defines the maximum number of results to return. (e.g., 10 (default) returns 10 results, 40 returns 40 results, and 100 returns 100 results).
     */
    public function handle(
        string $query,
        ?string $searchType = 'search',
        ?int $numberOfResults = 10
    ): string {

        $serpApiConnector = new SerpApiConnector($this->apiKey);
        $serpApiSearchRequest = new SerpApiSearchRequest($query, $numberOfResults);

        $results = $serpApiConnector->send($serpApiSearchRequest)->array();

        return $this->parseResults($results);
    }

    private function parseResults(array $results): string
    {

        $snippets = collect();
        if (! empty($results['knowledge_graph'])) {
            $knowledgeGraph = Arr::get($results, 'knowledge_graph');
            $title = Arr::get($knowledgeGraph, 'title', '');

            $description = Arr::get($knowledgeGraph, 'description');
            if ($description) {
                $snippets->push($description);
                $snippets->push(['type' => 'Knowledge Graph Description', 'value' => $description]);
            }
            foreach ($knowledgeGraph as $key => $value) {
                if (
                    is_string($key) &&
                    is_string($value) &&
                    ($key !== 'title' && $key !== 'description') &&
                    ! Str::endsWith($key, '_stick') &&
                    ! Str::endsWith($key, '_link') &&
                    ! Str::startsWith($value, 'http')
                ) {
                    $snippets->push(['type' => 'Knowledge Graph Attribute', 'title' => $title, 'key' => $key, 'value' => $value]);
                }
            }
        }

        if (! empty($results['organic_results'])) {
            $organicResults = Arr::get($results, 'organic_results');

            foreach ($organicResults as $organicResult) {
                $snippets->push(['type' => 'Organic', 'title' => $organicResult['title'], 'link' => $organicResult['link'], 'snippet' => $organicResult['snippet']]);
            }
        }

        if ($snippets->isEmpty()) {
            return json_encode(['title' => 'No Good Google Search Result was found', 'snippet' => '', 'link' => ''], JSON_PRETTY_PRINT);
        }

        return json_encode($snippets, JSON_PRETTY_PRINT);
    }
}
