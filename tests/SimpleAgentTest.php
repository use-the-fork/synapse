<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agents\SimpleAgent;

it('can do a simple query', function () {
  $agent = new SimpleAgent();
  $agentResponse = $agent->handle(['query' => 'search google for the current president of the united states.']);

  $agentResponse = $agent->handle(['query' => 'search google for the current president of the united states.']);

  expect($agentResponse)->toBeArray()
                        ->and($agentResponse)->toHaveKey("answer");
})->only();
