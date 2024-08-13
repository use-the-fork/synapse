<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agent;
  use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;
  use UseTheFork\Synapse\Tools\SerpAPIGoogleNewsTool;
  use UseTheFork\Synapse\Tools\SerpAPIGoogleSearchTool;

  class SerpAPIGoogleNewsToolAgent extends Agent
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
        new SerpAPIGoogleNewsTool(),
      ];
    }
  }

it('can search google pages using SerpAPI', function () {

  $agent = new SerpAPIGoogleNewsToolAgent();
  $agentResponse = $agent->handle(['input' => 'what are the latest headlines in relation to Apple']);

  expect($agentResponse)->toBeArray()
                        ->and($agentResponse)->toHaveKey("answer");
});
