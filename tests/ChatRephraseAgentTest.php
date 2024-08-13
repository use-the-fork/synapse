<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agents\ChatRephraseAgent;

it('can run the Chat Rephrase Agent.', function () {
    $agent = new ChatRephraseAgent();

    $agentResponse = $agent->handle(['query' => 'whats her name?']);

    expect($agentResponse)->toBeArray()
        ->and($agentResponse)->toHaveKey('answer')
        ->and($agentResponse['answer'])->toBeArray()
        ->and($agentResponse['answer'])->toHaveCount(5);
});
