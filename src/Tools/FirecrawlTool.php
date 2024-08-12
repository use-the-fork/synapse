<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\FirecrawlService;
use UseTheFork\Synapse\Services\SerperService;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Useful for getting the contents of a webpage.')]
final class FirecrawlTool
{

    public function __construct(
      private readonly string $apiKey
    ) {
    }

    public function handle(
        #[Description('the full URL to get the contents from')]
        string $url,
    ): string {

        $firecrawlService = new FirecrawlService($this->apiKey);
        $results = $firecrawlService->__invoke($url);

        return $this->parseResults($results);
    }

    private function parseResults($results): string
    {
      $snippets = collect();

      if(!empty($results['data']['metadata']['title'])){
        $snippets->push("Meta Title: {$results['data']['metadata']['title']}");
      }

      if(!empty($results['data']['metadata']['description'])){
        $snippets->push("Meta Description: {$results['data']['metadata']['description']}");
      }

      if(!empty($results['data']['content'])){
        $snippets->push("Content:\n {$results['data']['content']}");
      }

      if(!empty($results['data']['linksOnPage'])){
        $snippets->push("Links On Page:");
        foreach ($results['data']['linksOnPage'] as $link) {
          $snippets->push("- {$link}");
        }
      }

      if($snippets->isEmpty()){
        return "Could not scrape page.";
      }

        return $snippets->implode("\n");
    }
}
