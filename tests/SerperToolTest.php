<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agent;
  use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;
  use UseTheFork\Synapse\Tools\SerperTool;

  class SerperTestAgent extends Agent
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
        new SerperTool(),
      ];
    }
  }

it('can search google pages', function () {

  $agent = new SerperTestAgent();
  $agentResponse = $agent->handle(['input' => 'search google for the current president of the united states.']);

  expect($agentResponse)->toBeArray()
                        ->and($agentResponse)->toHaveKey("answer");
});
