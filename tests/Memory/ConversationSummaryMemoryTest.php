<?php

declare(strict_types=1);

    use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Agent;
    use UseTheFork\Synapse\Contracts\Agent\HasMemory;
    use UseTheFork\Synapse\Contracts\Integration;
    use UseTheFork\Synapse\Contracts\Memory;
    use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
    use UseTheFork\Synapse\Integrations\OpenAIIntegration;
    use UseTheFork\Synapse\Memory\ConversationSummaryMemory;
    use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
    use UseTheFork\Synapse\Tools\SerperTool;
    use UseTheFork\Synapse\Traits\Agent\ManagesMemory;
    use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
    use UseTheFork\Synapse\ValueObject\SchemaRule;

    it('Conversation Summary Memory', function (): void {

    class ConversationSummaryAgent extends Agent implements HasMemory
    {
        use ManagesMemory;
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

        public function resolveMemory(): Memory
        {
            return new ConversationSummaryMemory;
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Memory/ConversationSummaryAgent-{$hash}");
        },
    ]);

    $agent = new ConversationSummaryAgent;
    $message = $agent->handle(['input' => 'hello this a test']);
    $agentResponseArray = $message->toArray();

    $memory = $agent->memory()->get();

    expect($memory[0]->content())->toContain('The user\'s message says "hello this a test". The assistant responded with "Hello! How can I assist you today?')
                                 ->and($agentResponseArray['content'])->toBeArray()
                                 ->and($agentResponseArray['content'])->toHaveKey('answer')
                                 ->and($agentResponseArray['content']['answer'])->toBe('Hello! How can I assist you today?');

    $followup = $agent->handle(['input' => 'what did I just say? But Backwards.']);

    $memory = $agent->memory()->get();

    $followupResponseArray = $followup->toArray();
    expect($memory[0]->content())->toContain('The user\'s message says "hello this a test". The assistant responded with "Hello! How can I assist you today?" Following this, the user asked what they just said but backwards. The assistant replied with "tset a si siht olleh".')
                                 ->and($followupResponseArray['content'])->toBeArray()
                                 ->and($followupResponseArray['content'])->toHaveKey('answer')
                                 ->and($followupResponseArray['content']['answer'])->toBe('tset a si siht olleh');

});

    it('Conversation Summary Memory With Tools', function (): void {

    class ConversationSummaryWithToolsAgent extends Agent implements HasMemory
    {
        use ManagesMemory;
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

        public function resolveMemory(): Memory
        {
            return new ConversationSummaryMemory;
        }

        protected function resolveTools(): array
        {
            return [new SerperTool];
        }

    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Memory/ConversationSummaryWithToolsAgent-{$hash}");
        },
        SerperSearchRequest::class  => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Memory/ConversationSummaryWithToolsAgent-Tool-{$hash}");
        },
    ]);

    $agent = new ConversationSummaryWithToolsAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);
    $agentResponseArray = $message->toArray();

    $memory = $agent->memory()->get();

    expect($memory[0]->content())->toContain('The user requested to search Google to find out who the current President of the United States is. The search returned results identifying Joe Biden as the current President, serving as the 46th president since 2021. Various sources including the official White House website and Wikipedia confirm this information. The assistant affirmed that Joe Biden is the current President of the United States.')
                                 ->and($agentResponseArray['content'])->toBeArray()
                                 ->and($agentResponseArray['content'])->toHaveKey('answer')
                                 ->and($agentResponseArray['content']['answer'])->toBe('Joe Biden is the current President of the United States.');

    $followup = $agent->handle(['input' => 'What about the Vice President?']);

    $memory = $agent->memory()->get();

    $followupResponseArray = $followup->toArray();
    expect($memory[0]->content())->toContain('The user inquired about the current Vice President of the United States following their earlier question about the President, and was informed that the current Vice President is Kamala Harris.')
                                 ->and($followupResponseArray['content'])->toBeArray()
                                 ->and($followupResponseArray['content'])->toHaveKey('answer')
                                 ->and($followupResponseArray['content']['answer'])->toBe('The current Vice President of the United States is Kamala Harris.');

});
