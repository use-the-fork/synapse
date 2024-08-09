<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\SerperService;

#[Description('Search Google using a query.')]
final class SearchGoogleTool
{
    public function handle(
        #[Description('the search query to execute')]
        string $query,
    ): string {

        $serperService = new SerperService();
        $results = $serperService->__invoke($query);

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
          $snippets->push($value['snippet']);
        }
      }

      if($snippets->isEmpty()){
        return "No good Google Search Result was found";
      }

        return $snippets->implode("\n");
    }
}