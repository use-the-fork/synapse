<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Services\Serper\SerperConnector;

final class SerperTool extends BaseTool implements Tool
{
    private string $apiKey;


    public function boot(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        $this->apiKey = config('synapse.services.serper.key');

        if(empty($this->apiKey)) {
            throw new MissingApiKeyException('API (SERPER_API_KEY) key is required.');
        }

        return $pendingAgentTask;
    }

    /**
     * Search Google using a query.
     *
     * @param string $query the search query to execute.
     * @param string $searchType the type of search must be one of `search`, `places`, `news`.  (usually search).
     * @param int $numberOfResults the number of results to return must be one of `10`, `20`, `30`, `40`, `50` (usually `10`).
     *
     */
    public function handle(
        string $query,
        string $searchType = 'search',
        int $numberOfResults = 10,
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
