<?php

declare(strict_types=1);

use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Tools\BaseTool;
use UseTheFork\Synapse\Tools\SerperTool;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

test('Serper Tool', function (): void {

    class SerperTestAgent extends Agent implements HasOutputSchema
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
        ChatRequest::class => function (PendingRequest $pendingRequest): Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Tools/SerperTestAgent-{$hash}");
        },
        SerperSearchRequest::class => MockResponse::fixture('Tools/Serper-Tool'),
    ]);

    $agent = new SerperTestAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');

});

test('Architecture', function (): void {

    expect(SerperTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
