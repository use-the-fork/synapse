<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

it('can do a simple query', function (): void {

    class CollectionMemoryAgent extends Agent
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
        }

        protected function resolveOutputSchema(): array
        {
            return [
                SchemaRule::make([
                    'name' => 'answer',
                    'rules' => 'required|string',
                    'description' => 'your final answer to the query.',
                ]),
            ];
        }

        public function resolveMemory(): Memory
        {
            return new CollectionMemory;
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("memory/collection-{$hash}");
        },
    ]);

    $agent = new CollectionMemoryAgent;
    $message = $agent->handle(['input' => 'hello this a test']);
    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toBe('Hello! How can I assist you today?');

    $followup = $agent->handle(['input' => 'what did I just say? But Backwards.']);
    $followupResponseArray = $followup->toArray();
    expect($followupResponseArray['content'])->toBeArray()
        ->and($followupResponseArray['content'])->toHaveKey('answer')
        ->and($followupResponseArray['content']['answer'])->toBe('?sdrawkcaB .yas tsuj I did tahw');

});
