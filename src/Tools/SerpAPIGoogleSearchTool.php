<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\SerpAPIService;
use UseTheFork\Synapse\Services\SerperService;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Search Google using a query.')]
final class SerpAPIGoogleSearchTool extends BaseTool implements Tool
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
      if (empty($this->apiKey) && ! empty(env('SERPAPI_API_KEY'))) {
        $this->apiKey = env('SERPAPI_API_KEY');

        return;
      }
      throw new \Exception('API (SERPAPI_API_KEY) key is required.');
    }

    public function handle(
        #[Description('Parameter defines the query you want to search. You can use anything that you would use in a regular Google search. e.g. `inurl:`, `site:`, `intitle:`.')]
        string $query,
        #[Description('Parameter defines the maximum number of results to return. (e.g., 10 (default) returns 10 results, 40 returns 40 results, and 100 returns 100 results)')]
        int $numberOfResults = 10,
    ): string {

        $this->log('Entered', [
          'query'              => $query,
          'numberOfResults' => $numberOfResults,
        ]);

        $serperService = new SerpAPIService($this->apiKey);
        $results = $serperService->__invoke($query, $numberOfResults);
        $this->log('Finished', ['results' => $results]);

        return $this->parseResults($results);
    }

    private function parseResults($results): string
    {

      $snippets = collect();
      if(!empty($results['knowledge_graph'])){
        $knowledgeGraph = Arr::get($results, 'knowledge_graph');
        $title = Arr::get($knowledgeGraph, 'title', "");

        $description = Arr::get($knowledgeGraph, 'description');
        if($description){
          $snippets->push($description);
        }
        foreach ($knowledgeGraph as $key => $value){
          if(
            is_string($key) &&
            is_string($value) &&
            ($key != "title" && $key != "description") &&
            !Str::endsWith($key,'_stick') &&
            !Str::endsWith($key,'_link') &&
            !Str::startsWith($value,'http')
          ){
            $snippets->push("{$title} {$key}: {$value}.");
          }
        }
      }

      if(!empty($results['organic_results'])){
        $organicResults = Arr::get($results, 'organic_results');

        foreach ($organicResults as $key => $value){
          $snippets->push("```text\nTitle: {$value['title']}\nLink: {$value['link']}\nSnippet: {$value['snippet']}\n```");
        }
      }

      if($snippets->isEmpty()){
        return "No good Google Search Result was found";
      }

        return $snippets->implode("\n");
    }
}
