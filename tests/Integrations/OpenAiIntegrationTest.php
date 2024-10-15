<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasIntegration;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Tools\SerperTool;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

test('Connects with out resolveIntegration', function (): void {

    class OpenAiWithOutResolveTestAgent extends Agent implements HasOutputSchema
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

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
    }

    MockClient::global([
        ChatRequest::class => MockResponse::fixture('Integrations/OpenAiWithOutResolveTestAgent'),
    ]);

    $agent = new OpenAiWithOutResolveTestAgent;
    $message = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');
});

test('Connects', function (): void {

    class OpenAiTestAgent extends Agent implements HasIntegration, HasOutputSchema
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
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
    }

    MockClient::global([
        ChatRequest::class => MockResponse::fixture('Integrations/OpenAiTestAgent'),
    ]);

    $agent = new OpenAiTestAgent;
    $message = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');
});

test('Connects With OutputSchema', function (): void {

    class OpenAiConnectsTestAgent extends Agent implements HasIntegration
    {
        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
        }
    }

    MockClient::global([
        ChatRequest::class => MockResponse::fixture('Integrations/OpenAiTestAgent'),
    ]);

    $agent = new OpenAiConnectsTestAgent;
    $message = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $message->toArray();
    expect($agentResponseArray['content'])->not->toBeArray();
});

test('uses tools', function (): void {

    class OpenAiToolTestAgent extends Agent implements HasIntegration, HasOutputSchema
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
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
            return [new SerperTool];
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Integrations/OpenAiToolTestAgent-{$hash}");
        },
        SerperSearchRequest::class => MockResponse::fixture('Integrations/OpenAiToolTestAgent-Serper-Tool'),
    ]);

    $agent = new OpenAiToolTestAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');
});
