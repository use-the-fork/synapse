<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agents\MultiQueryRetrieverAgent;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;

it('can run the Multi Query Retriever Agent.', function (): void {

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $count = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Agents/MultiQueryRetrieverAgent-{$count}");
        },
    ]);

    $agent = new MultiQueryRetrieverAgent;

    $message = $agent->handle(['queryCount' => '5', 'input' => 'What gym activities do you recommend for heart health?']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toBeArray()
        ->and($agentResponseArray['content']['answer'])->toHaveCount(5);

});
