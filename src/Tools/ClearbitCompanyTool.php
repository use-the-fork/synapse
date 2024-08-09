<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\ClearbitService;
use UseTheFork\Synapse\Services\SerperService;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Search Clearbit Company data.')]
final class ClearbitCompanyTool
{
    public function __construct(
      private readonly string $apiKey
    ) {
    }


    public function handle(
        #[Description('the Top Level domain name to lookup for example `clearbit.com`')]
        string $domain,
    ): string {

        $clearbitService = new ClearbitService($this->apiKey);
        $results = $clearbitService->searchCompany($domain);

        return $this->parseResults($results);
    }

    private function parseResults($results): string
    {

      dd($results);

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
