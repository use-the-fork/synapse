<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Events\Agent\AgentFinish;
use UseTheFork\Synapse\Events\Agent\BootAgent;
use UseTheFork\Synapse\Events\Agent\EndIteration;
use UseTheFork\Synapse\Events\Agent\EndThread;
use UseTheFork\Synapse\Events\Agent\EndToolCall;
use UseTheFork\Synapse\Events\Agent\IntegrationResponse;
use UseTheFork\Synapse\Events\Agent\PromptGenerated;
use UseTheFork\Synapse\Events\Agent\PromptParsed;
use UseTheFork\Synapse\Events\Agent\StartIteration;
use UseTheFork\Synapse\Events\Agent\StartThread;
use UseTheFork\Synapse\Events\Agent\StartToolCall;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
use UseTheFork\Synapse\Tools\Search\SerperTool;
use UseTheFork\Synapse\Traits\Agent\HandlesAgentEvents;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

test('Handles Agent Events', function (): void {

    Event::fake();

    class HandlesAgentEventsTestAgent extends Agent implements HasOutputSchema
    {
        use HandlesAgentEvents;
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

    $agent = new HandlesAgentEventsTestAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');

    Event::assertDispatched(BootAgent::class);
    Event::assertDispatched(StartThread::class);
    Event::assertDispatched(PromptGenerated::class);
    Event::assertDispatched(PromptParsed::class);
    Event::assertDispatched(IntegrationResponse::class);
    Event::assertDispatched(StartToolCall::class);
    Event::assertDispatched(EndToolCall::class);
    Event::assertDispatched(StartIteration::class);
    Event::assertDispatched(EndIteration::class);
    Event::assertDispatched(AgentFinish::class);
    Event::assertDispatched(EndThread::class);

});
