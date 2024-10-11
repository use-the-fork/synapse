<?php

declare(strict_types=1);

    use Illuminate\Support\Facades\Event;
    use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Agent;
    use UseTheFork\Synapse\AgentTask\PendingAgentTask;
    use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasAgentFinishHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasBootAgentHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasEndIterationHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasEndThreadHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasEndToolCallHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasIntegrationResponseHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasPromptGeneratedHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasPromptParsedHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasStartIterationHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasStartThreadHook;
    use UseTheFork\Synapse\Contracts\Agent\Hooks\HasStartToolCallHook;
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
    use UseTheFork\Synapse\Tools\SerperTool;
    use UseTheFork\Synapse\Traits\Agent\ManagesHooks;
    use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
    use UseTheFork\Synapse\ValueObject\SchemaRule;

    test('Handles Agent Events', closure: function (): void {

    Event::fake();

    class ManagesHooksTestAgent extends Agent implements HasBootAgentHook, HasEndIterationHook, HasEndThreadHook, HasEndToolCallHook, HasIntegrationResponseHook, HasOutputSchema, HasPromptGeneratedHook, HasPromptParsedHook, HasStartIterationHook, HasStartThreadHook, HasStartToolCallHook, HasAgentFinishHook
    {
        use ManagesHooks;
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

        public function hookStartThread(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            StartThread::dispatch($pendingAgentTask);

            return $pendingAgentTask;

        }

        public function hookBootAgent(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            BootAgent::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }

        public function hookEndIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            EndIteration::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }

        public function hookEndThread(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            EndThread::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }

        public function hookEndToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            EndToolCall::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }

        public function hookIntegrationResponse(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            IntegrationResponse::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }

        public function hookPromptGenerated(string $generatedPrompt): string
        {
            PromptGenerated::dispatch($generatedPrompt);

            return $generatedPrompt;
        }

        public function hookPromptParsed(array $parsedPrompt): array
        {
            PromptParsed::dispatch($parsedPrompt);

            return $parsedPrompt;
        }

        public function hookStartIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            StartIteration::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }

        public function hookStartToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            StartToolCall::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }

        public function hookAgentFinish(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            AgentFinish::dispatch($pendingAgentTask);

            return $pendingAgentTask;
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Integrations/OpenAiToolTestAgent-{$hash}");
        },
        SerperSearchRequest::class => MockResponse::fixture('Integrations/OpenAiToolTestAgent-Serper-Tool'),
    ]);

    $agent = new ManagesHooksTestAgent;
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
