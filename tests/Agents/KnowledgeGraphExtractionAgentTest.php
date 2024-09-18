<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agents\ChatRephraseAgent;
    use UseTheFork\Synapse\Agents\KnowledgeGraphExtractionAgent;
    use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\Enums\ResponseType;
use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;

it('can run the Knowledge Graph Extraction Agent.', function () {

//    MockClient::global([
//        ChatRequest::class => function (PendingRequest $pendingRequest) {
//            $count = count($pendingRequest->body()->get('messages'));
//
//            return MockResponse::fixture("agents/chat-rephrase-agent/message-{$count}");
//        },
//    ]);

    $agent = new KnowledgeGraphExtractionAgent;

    $agentResponse = $agent->handle(['input' => 'The ML2000 Series is an ANSI/BHMA Grade 1 mortise lock designed to meet the rigors of high-traffic, abusive environments. Constructed of heavy-gauge steel with unique, patented features and a full range of trim and functions including status indicators and Motorized Electric Latch Retraction (MELR), the versatility and reliability of this lock complement any application.']);

    dd($agentResponse);

    expect($agentResponse)->toBeArray()
        ->and($agentResponse)->toHaveKey('standalone_question')
        ->and($agentResponse['standalone_question'] == 'What are some methods to improve heart health?')->toBeTrue();
});
