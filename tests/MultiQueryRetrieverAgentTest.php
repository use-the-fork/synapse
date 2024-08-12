<?php

declare(strict_types=1);

use UseTheFork\Synapse\Agents\MultiQueryRetrieverAgent;

it('can handle multiple inputs for a query.', function () {
    $agent = new MultiQueryRetrieverAgent();
    $agentResponse = $agent->handle(['query' => 'search google for the current president of the united states.', 'queryCount' => 5]);

    expect($agentResponse)->toBeArray()
        ->and($agentResponse)->toHaveKey('answer')
        ->and($agentResponse['answer'])->toBeArray()
        ->and($agentResponse['answer'])->toHaveCount(5);
});
