<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\AgentChain;
use UseTheFork\Synapse\Agents\Rat\RatDraftAgent;
use UseTheFork\Synapse\Agents\Rat\RatSplitAnswerAgent;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;

it('executes a RAT chain', function (): void {

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("AgentChains/RatAgentChain-{$hash}");
        },
    ]);

    $agentChain = AgentChain::make([
        new RatDraftAgent,
        new RatSplitAnswerAgent,
    ])->persistInputs([
        'question' => 'how so I improve my heart health?',
        'number_of_paragraphs' => '5',
    ]);

    $message = $agentChain->handle([]);
    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('paragraphs')
        ->and($agentResponseArray['content']['paragraphs'])->toBeArray();

    $answer = '';
    foreach ($agentResponseArray['content']['paragraphs'] as $paragraph) {
        $answer = "{$answer}\n\n{$paragraph}";
    }

});
