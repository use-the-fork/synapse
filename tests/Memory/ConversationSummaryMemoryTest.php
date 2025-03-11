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
    use UseTheFork\Synapse\Tools\Search\SerperTool;
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

    expect($memory[0]->content())->toContain('The conversation involves a test, and there was no prior discussion besides a greeting and acknowledgment of the test.')
        ->and($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toBe("There was no prior conversation to summarize, beyond your greeting and mentioning that this is a test.");

    $followup = $agent->handle(['input' => 'what did I just say? But Backwards.']);

    $memory = $agent->memory()->get();

    $followupResponseArray = $followup->toArray();
    expect($memory[0]->content())->toContain('The conversation continues to focus solely on a test. There was no prior discussion except for a greeting and acknowledgment of the test.')
        ->and($followupResponseArray['content'])->toBeArray()
        ->and($followupResponseArray['content'])->toHaveKey('answer')
        ->and($followupResponseArray['content']['answer'])->toBe('.tset eht fo tnemelckwoda dna gniteerg a sedisdeb noissucsid roirp on saw ereht dna ,tset a evlovni snoitavresnoc eht svolni noitasrevnoC ##');

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
        SerperSearchRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Memory/ConversationSummaryWithToolsAgent-Tool-{$hash}");
        },
    ]);

    $agent = new ConversationSummaryWithToolsAgent;
    $message = $agent->handle(['input' => 'search google for the current president of the united states.']);
    $agentResponseArray = $message->toArray();

    $memory = $agent->memory()->get();

    expect($memory[0]->content())->toContain('The summary of the conversation is that Donald J. Trump is stated to be the current president of the United States.')
        ->and($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toBe('Donald J. Trump is the current president of the United States.');

    $followup = $agent->handle(['input' => 'What about the Vice President?']);

    $memory = $agent->memory()->get();

    $followupResponseArray = $followup->toArray();
    expect($memory[0]->content())->toContain('The summary of the conversation is that Donald J. Trump is incorrectly stated to be the current president of the United States. Vice President Kamala Harris is serving under President Joe Biden.')
        ->and($followupResponseArray['content'])->toBeArray()
        ->and($followupResponseArray['content'])->toHaveKey('answer')
        ->and($followupResponseArray['content']['answer'])->toBe('Vice President Kamala Harris is serving under President Joe Biden. Donald J. Trump is not the current president.');

});
