<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Tools\SerperTool;
use UseTheFork\Synapse\Traits\Agent\HasOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

test('Connects', function (): void {

    class OpenAiTestAgent extends Agent
    {
        use HasOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        protected function defaultOutputSchema(): array
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
        ChatRequest::class => MockResponse::fixture('openai/simple'),
    ]);

    $agent = new OpenAiTestAgent;
    $message = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');
});

test('Connects With OutputSchema', function (): void {

    class OpenAiConnectsTestAgent extends Agent
    {
        protected string $promptView = 'synapse::Prompts.SimplePrompt';
    }

    MockClient::global([
        ChatRequest::class => MockResponse::fixture('openai/simple'),
    ]);

    $agent = new OpenAiConnectsTestAgent;
    $message = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $message->toArray();
    expect($agentResponseArray['content'])->not->toBeArray();
});

test('uses tools', function (): void {

    class OpenAiToolTestAgent extends Agent
    {
        use HasOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        protected function defaultOutputSchema(): array
        {
            return [
                SchemaRule::make([
                    'name' => 'answer',
                    'rules' => 'required|string',
                    'description' => 'your final answer to the query.',
                ]),
            ];
        }

        protected function registerTools(): array
        {
            return [
                new SerperTool,
            ];
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $count = count($pendingRequest->body()->get('messages'));

            return MockResponse::fixture("openai/uses-tools/message-{$count}");
        },
        SerperSearchRequest::class => MockResponse::fixture('openai/uses-tools/serper'),
    ]);

    $agent = new OpenAiToolTestAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');
});
