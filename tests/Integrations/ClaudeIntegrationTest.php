<?php

declare(strict_types=1);

use Saloon\Http\Connector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Integrations\Connectors\Claude\ClaudeAIConnector;
use UseTheFork\Synapse\Integrations\Connectors\Claude\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\Connectors\Claude\Requests\ValidateOutputRequest;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Tools\SerperTool;
use UseTheFork\Synapse\ValueObject\SchemaRule;

test('Connects', function (): void {

    class ClaudeTestAgent extends Agent
    {
        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        protected function registerIntegration(): Connector
        {
            return new ClaudeAIConnector;
        }

        protected function registerOutputSchema(): array
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
        ChatRequest::class => MockResponse::fixture('claude/simple'),
    ]);

    $agent = new ClaudeTestAgent;
    $message = $agent->handle(['input' => 'hello!']);

    expect($message)->toBeArray()
        ->and($message)->toHaveKey('answer');
});

test('uses tools', function (): void {

    class ClaudeToolTestAgent extends Agent
    {
        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        protected function registerIntegration(): Connector
        {
            return new ClaudeAIConnector;
        }

        protected function registerOutputSchema(): array
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

            return MockResponse::fixture("claude/uses-tools/message-{$count}");
        },
        ValidateOutputRequest::class => MockResponse::fixture('claude/uses-tools/validate'),
        SerperSearchRequest::class => MockResponse::fixture('claude/uses-tools/serper'),
    ]);

    $agent = new ClaudeToolTestAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);

    expect($message)->toBeArray()
        ->and($message)->toHaveKey('answer');
});
