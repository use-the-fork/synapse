<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agents\SimpleAgent;

it('can do a simple query', function () {
  $agent = new SimpleAgent();
  $agentResponse = $agent->handle(['query' => 'summarize the content of "https://www.firecrawl.dev/smart-crawl"']);

  expect($agentResponse)->toBeArray()
                        ->and($agentResponse)->toHaveKey("answer");
});
