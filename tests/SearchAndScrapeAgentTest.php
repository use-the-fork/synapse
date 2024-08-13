<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agents\SearchAndScrapeAgent;

it('can do research using tools', function () {
  $agent = new SearchAndScrapeAgent();
  $agentResponse = $agent->handle(['input' => 'Research InVue Security Products (invue.com) located in Charlotte, NC. Focus on Strengths I would like at least 10.']);

  dd($agentResponse);

  expect($agentResponse)->toBeArray()
                        ->and($agentResponse)->toHaveKey("answer");
});
