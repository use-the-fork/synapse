<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agent;
  use UseTheFork\Synapse\Memory\CollectionMemory;
  use UseTheFork\Synapse\Memory\Contracts\Memory;
  use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

  class CollectionMemoryAgent extends Agent
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

    protected function registerMemory(): Memory
    {
      return new CollectionMemory();
    }
  }

it('can do a simple query', function () {
  $agent = new CollectionMemoryAgent();
  $agentResponse = $agent->handle(['query' => 'hello this a test']);

  expect($agentResponse)->toBeArray()
                        ->and($agentResponse)->toHaveKey("answer");
});
