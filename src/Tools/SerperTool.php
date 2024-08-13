<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\SerperService;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Search Google using a query.')]
final class SerperTool extends BaseTool implements Tool
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
      if (empty($this->apiKey) && ! empty(env('SERPER_API_KEY'))) {
        $this->apiKey = env('SERPER_API_KEY');

        return;
      }
      throw new \Exception('API (SERPER_API_KEY) key is required.');
    }

    public function handle(
        #[Description('the search query to execute')]
        string $query,
        #[Description('the type of search must be one of `search`, `places`, `news`.  (usually search)')]
        string $searchType = "search",
        #[Description('the number of results to return must be one of `10`, `20`, `30`, `40`, `50` (usually `10`).')]
        int $numberOfResults = 10,
    ): string {

        $this->log('Entered', [
          'query'              => $query,
          'searchType' => $searchType,
          'numberOfResults' => $numberOfResults,
        ]);

        $serperService = new SerperService($this->apiKey);
        $results = $serperService->__invoke($query, $searchType, $numberOfResults);
        $this->log('Finished', ['results' => $results]);

        return $this->parseResults($results);
    }

    private function parseResults($results): string
    {

      $snippets = collect();
      if(!empty($results['knowledgeGraph'])){
        $title = Arr::get($results, 'knowledgeGraph.title');
        $entityType = Arr::get($results, 'knowledgeGraph.type');
        if($entityType){
          $snippets->push("{$title}: {$entityType}");
        }
        $description = Arr::get($results, 'knowledgeGraph.description');
        if($description){
          $snippets->push($description);
        }

        foreach (Arr::get($results, 'knowledgeGraph.attributes', []) as $key => $value){
          $snippets->push("{$title} {$key}: {$value}");
        }
      }

      if(!empty($results['organic'])){
        foreach ($results['organic'] as $key => $value){
          $snippets->push("```text\nTitle: {$value['title']}\nLink: {$value['link']}\nSnippet: {$value['snippet']}\n```");
        }
      }

      if($snippets->isEmpty()){
        return "No good Google Search Result was found";
      }

        return $snippets->implode("\n");
    }
}
