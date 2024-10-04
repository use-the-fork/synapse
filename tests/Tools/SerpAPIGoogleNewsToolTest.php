<?php

declare(strict_types=1);

use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Services\SerpApi\Requests\SerpApiSearchRequest;
use UseTheFork\Synapse\Tools\BaseTool;
use UseTheFork\Synapse\Tools\SerpAPIGoogleNewsTool;
use UseTheFork\Synapse\Tools\SerpAPIGoogleSearchTool;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

test('Serp API Google News Tool', function (): void {

    class SerpAPIGoogleNewsToolTestAgent extends Agent implements HasOutputSchema
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
        }

        public function resolveMemory(): Memory
        {
            return new CollectionMemory;
        }

        public function resolveOutputSchema(): array
        {
            return [
                SchemaRule::make([
                    'name' => 'answer',
                    'rules' => 'required|string',
                    'description' => 'your final answer to the query.',
                ]),
            ];
        }

        protected function resolveTools(): array
        {
            return [new SerpAPIGoogleNewsTool];
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Tools/SerpAPIGoogleNewsTool-{$hash}");
        },
        SerpApiSearchRequest::class => MockResponse::fixture('Tools/SerpAPIGoogleNewsTool-Tool'),
    ]);

    $agent = new SerpAPIGoogleNewsToolTestAgent;
    $message = $agent->handle(['input' => 'What are the current headlines about Apple?']);

    $agentResponseArray = $message->toArray();
    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toContain("Recent headlines about Apple include news on the expected discontinuation of some Apple products next month, the debut of macOS, iPadOS, and watchOS updates, and details on the newly unveiled iPhone 16 at the 'Glowtime' event.");

});

test('Architecture', function (): void {

    expect(SerpAPIGoogleSearchTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
