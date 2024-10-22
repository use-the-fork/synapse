<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools\Search;

use Illuminate\Support\Arr;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use UseTheFork\Synapse\Contracts\Tool\SearchTool;
use UseTheFork\Synapse\Enums\ReturnType;
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

        if(empty($this->apiKey)) {
            throw new MissingApiKeyException('API (SERPER_API_KEY) key is required.');
        }
    }

    /**
     * Search Google using a query.
     *
     * @param string      $query           the search query to execute.
     * @param string $searchType      the type of search must be one of `search`, `places`, `news`.  (usually search).
     * @param int    $numberOfResults the number of results to return must be one of `10`, `20`, `30`, `40`, `50` (usually `10`).
     *
     * @return string
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function handle(
        string $query,
        ?string $searchType = 'search',
        ?int $numberOfResults = 10,
        ReturnType $returnType = ReturnType::STRING,
    ): string|array {

        $serperConnector = new SerperConnector($this->apiKey);
        $serperSearchRequest = new SerperSearchRequest($query, $searchType, $numberOfResults);
        $results = $serperConnector->send($serperSearchRequest)->array();

        return match ($returnType) {
            ReturnType::STRING => $this->parseResults($results),
            default => $results
        };
    }

    private function parseResults(array $results): string
    {

        $snippets = collect();
        if (! empty($results['knowledgeGraph'])) {
            $title = Arr::get($results, 'knowledgeGraph.title');
            $entityType = Arr::get($results, 'knowledgeGraph.type');
            if ($entityType) {
                $snippets->push("{$title}: {$entityType}");
            }
            $description = Arr::get($results, 'knowledgeGraph.description');
            if ($description) {
                $snippets->push($description);
            }

            foreach (Arr::get($results, 'knowledgeGraph.attributes', []) as $key => $value) {
                $snippets->push("{$title} {$key}: {$value}");
            }
        }

        if (! empty($results['organic'])) {
            foreach ($results['organic'] as $value) {
                $snippets->push("```text\nTitle: {$value['title']}\nLink: {$value['link']}\nSnippet: {$value['snippet']}\n```");
            }
        }

        if ($snippets->isEmpty()) {
            return 'No good Google Search Result was found';
        }

        return $snippets->implode("\n");
    }
}
