<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agent;
  use UseTheFork\Synapse\Agents\SimpleAgent;
  use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;
  use UseTheFork\Synapse\Tools\FirecrawlTool;

  class FireCrawlTestAgent extends Agent
  {
    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    protected function registerOutputRules(): array
    {
      return [
        OutputRule::make([
                           'name' => 'answer',
                           'rules' => 'required|string',
                           'description' => 'your final answer to the query.',
                         ]),
      ];
    }

    protected function registerTools(): array
    {
      return [
        new FirecrawlTool(),
      ];
    }
  }

it('can scrape pages', function () {

  $agent = new FireCrawlTestAgent();
  $agentResponse = $agent->handle(['input' => 'summarize the content of "https://www.firecrawl.dev/smart-crawl"']);

  expect($agentResponse)->toBeArray()
                        ->and($agentResponse)->toHaveKey("answer");
});
