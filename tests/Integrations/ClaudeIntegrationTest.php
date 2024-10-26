<?php

declare(strict_types=1);

    use Saloon\Http\Faking\Fixture;
    use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Agent;
    use UseTheFork\Synapse\Contracts\Agent\HasIntegration;
    use UseTheFork\Synapse\Contracts\Integration;
    use UseTheFork\Synapse\Contracts\Memory;
    use UseTheFork\Synapse\Integrations\ClaudeIntegration;
    use UseTheFork\Synapse\Integrations\Connectors\Claude\Requests\ChatRequest;
    use UseTheFork\Synapse\Memory\CollectionMemory;
    use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
    use UseTheFork\Synapse\Tools\Search\SerperTool;
    use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
    use UseTheFork\Synapse\ValueObject\SchemaRule;

    test('Connects', function (): void {

    class ClaudeTestAgent extends Agent implements HasIntegration
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new ClaudeIntegration;
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
        ChatRequest::class => MockResponse::fixture('Integrations/ClaudeTestAgent'),
    ]);

    $agent = new ClaudeTestAgent;
    $message = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');
});

test('uses tools', function (): void {

    class ClaudeToolTestAgent extends Agent implements HasIntegration
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new ClaudeIntegration;
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
            return [new SerperTool];
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));
            return MockResponse::fixture("Integrations/ClaudeToolTestAgent-{$hash}");
        },
        SerperSearchRequest::class => MockResponse::fixture('Integrations/ClaudeTestAgent-Serper-Tool'),
    ]);

    $agent = new ClaudeToolTestAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);

    $agentResponseArray = $message->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toContain('The current president of the United States is Joe Biden. He is the 46th president of the United States, having taken office in 2021. Biden previously served as the 47th Vice President of the United States and represented Delaware in the U.S. Senate for 36 years before becoming president.');
});
