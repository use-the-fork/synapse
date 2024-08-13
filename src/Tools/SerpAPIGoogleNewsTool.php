<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\SerpAPIService;
use UseTheFork\Synapse\Tools\Contracts\Tool;

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
      if (empty($this->apiKey) && ! empty(env('SERPAPI_API_KEY'))) {
        $this->apiKey = env('SERPAPI_API_KEY');

        return;
      }
      throw new \Exception('API (SERPAPI_API_KEY) key is required.');
    }

    public function handle(
        #[Description('Parameter defines the query you want to search. You can use anything that you would use in a regular Google News search. e.g. `site:`, `when:`')]
        string $query,
    ): string {

        $this->log('Entered', [
          'query'              => $query,
        ]);

        $serperService = new SerpAPIService($this->apiKey);
        $results = $serperService->__invoke($query, ['engine' => 'google_news']);
        $this->log('Finished');

        return $this->parseResults($results);
    }

    private function parseResults($results): string
    {

      $snippets = collect();

      if(!empty($results['news_results'])){
        $newsResults = Arr::get($results, 'news_results');

        foreach ($newsResults as $value){
          $result = collect();
          $result->push("```text\n### Title: {$value['title']}");

          if(!empty($value['stories'])) {
            foreach (Arr::get($value, 'stories', []) as $story) {

              $result->push("#### Story: {$story['title']}");
              $result->push("- Date: {$story['date']}");
              $result->push("- Link: {$story['link']}");
            }
          }
          if(!empty($value['source'])) {
            $result->push("- Date: {$value['date']}");
            $result->push("- Link: {$value['link']}");
          }
          $result->push("```\n");

          $snippets->push($result->implode("\n"));
        }
      }

      if($snippets->isEmpty()){
        return "No good Google News Result found";
      }

        return $snippets->implode("\n");
    }
}
