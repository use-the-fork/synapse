<?php

declare(strict_types=1);

use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\AgentChains\RatAgentChain;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Services\Firecrawl\Requests\FirecrawlRequest;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Tools\Scrape\FirecrawlTool;
use UseTheFork\Synapse\Tools\Search\SerperTool;

it('executes a RAT chain', function (): void {

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("AgentChains/Rat/RatAgentChain-{$hash}");
        },
        SerperSearchRequest::class => function (PendingRequest $pendingRequest): Fixture {

            $hash = md5(json_encode($pendingRequest->body()->all()));

            return MockResponse::fixture("AgentChains/Rat/RatAgentChainSerperTool-{$hash}");
        },
        FirecrawlRequest::class => function (PendingRequest $pendingRequest): Fixture {
            $hash = md5(json_encode($pendingRequest->body()->all()));

            return MockResponse::fixture("AgentChains/Rat/RatAgentChainFirecrawlTool-{$hash}");
        },
    ]);

    $ratAgentChain = new RatAgentChain(new SerperTool, new FirecrawlTool);
    $message = $ratAgentChain->handle(['question' => 'Summarize the American Civil War according to the timeline.', 'number_of_paragraphs' => '5']);
    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toContain('The American Civil War, which spanned from 1861 to 1865, ');

});
