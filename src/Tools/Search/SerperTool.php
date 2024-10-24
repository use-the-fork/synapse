<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools\Search;

use Illuminate\Support\Arr;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use UseTheFork\Synapse\Contracts\Tool\SearchTool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Services\Serper\SerperConnector;
use UseTheFork\Synapse\Tools\BaseTool;

final class SerperTool extends BaseTool implements SearchTool
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('synapse.services.serper.key');

        if (empty($this->apiKey)) {
            throw new MissingApiKeyException('API (SERPER_API_KEY) key is required.');
        }
    }

    /**
     * Search Google using a query.
     *
     * @param  string  $query  the search query to execute.
     * @param  string|null  $searchType  the type of search must be one of `search`, `places`, `news`.  (usually search).
     * @param  int|null  $numberOfResults  the number of results to return must be one of `10`, `20`, `30`, `40`, `50` (usually `10`).
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function handle(
        string $query,
        ?string $searchType = 'search',
        ?int $numberOfResults = 10
    ): string {

        $serperConnector = new SerperConnector($this->apiKey);
        $serperSearchRequest = new SerperSearchRequest($query, $searchType, $numberOfResults);
        $results = $serperConnector->send($serperSearchRequest)->array();

        return $this->parseResults($results);
    }

    private function parseResults(array $results): string
    {

        $snippets = collect();
        if (! empty($results['knowledgeGraph'])) {
            $title = Arr::get($results, 'knowledgeGraph.title');
            $entityType = Arr::get($results, 'knowledgeGraph.type');
            if ($entityType) {
                $snippets->push(['type' => 'Knowledge Graph title', "{$title}" => "{$entityType}"]);
            }
            $description = Arr::get($results, 'knowledgeGraph.description');
            if ($description) {
                $snippets->push($description);
                $snippets->push(['type' => 'Knowledge Graph Description', 'value' => $description]);
            }

            foreach (Arr::get($results, 'knowledgeGraph.attributes', []) as $key => $value) {
                $snippets->push(['type' => 'Knowledge Graph Attribute', 'title' => $title, 'key' => $key, 'value' => $value]);
            }
        }

        if (! empty($results['organic'])) {
            foreach ($results['organic'] as $value) {
                $snippets->push(['type' => 'Organic', 'title' => $value['title'], 'link' => $value['link'], 'snippet' => $value['snippet']]);
            }
        }

        if ($snippets->isEmpty()) {
            return json_encode(['title' => 'No Good Google Search Result was found', 'snippet' => '', 'link' => ''], JSON_PRETTY_PRINT);
        }

        return json_encode($snippets, JSON_PRETTY_PRINT);
    }
}
