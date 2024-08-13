<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\FirecrawlService;
use UseTheFork\Synapse\Services\SerperService;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Useful for getting the contents of a webpage.')]
final class FirecrawlTool extends BaseTool implements Tool
{

  private string $apiKey;

    public function __construct(?string $apiKey = null) {

      if(!empty($apiKey)){
        $this->apiKey = $apiKey;
      }

      parent::__construct();
    }

    protected function initializeTool(): void {
      if(empty($this->apiKey) && !empty(env('FIRECRAWL_API_KEY'))){
        $this->apiKey = env('FIRECRAWL_API_KEY');
        return;
      }
      throw new \Exception('API key is required.');
    }

    public function handle(
        #[Description('The full URL to get the contents from')]
        string $url,
        #[Description('A prompt describing what information to extract from the page')]
        string $extractionPrompt,
    ): string {

        Log::debug('Entered FirecrawlTool',['url' => $url, 'extractionPrompt' => $extractionPrompt]);
        $firecrawlService = new FirecrawlService($this->apiKey);
        $results = $firecrawlService->__invoke($url, $extractionPrompt);
        Log::debug('Finished FirecrawlTool',['results' => $results]);

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
