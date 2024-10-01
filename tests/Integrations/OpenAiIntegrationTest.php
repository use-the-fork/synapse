<?php

declare(strict_types=1);

    use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Agents\Agent;
    use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
    use UseTheFork\Synapse\OutputSchema\Traits\HasOutputSchema;
    use UseTheFork\Synapse\OutputSchema\ValueObjects\SchemaRule;
    use UseTheFork\Synapse\Services\Serper\Requests\SerperSearchRequest;
    use UseTheFork\Synapse\Tools\SerperTool;

test('Connects', function () {

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
    $agentResponse = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $agentResponse->toArray();

    expect($agentResponseArray['content'])->toBeArray()
                                          ->and($agentResponseArray['content'])->toHaveKey('answer');
});

test('Connects With OutputSchema', function () {

    class OpenAiTestAgent extends Agent
    {

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

    }

    MockClient::global([
        ChatRequest::class => MockResponse::fixture('openai/simple'),
    ]);

    $agent = new OpenAiTestAgent;
    $agentResponse = $agent->handle(['input' => 'hello!']);

    $agentResponseArray = $agentResponse->toArray();
    expect($agentResponseArray['content'])->not->toBeArray();
});

test('uses tools', function () {

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
        ChatRequest::class => function (PendingRequest $pendingRequest) {
            $count = count($pendingRequest->body()->get('messages'));

            return MockResponse::fixture("openai/uses-tools/message-{$count}");
        },
        SerperSearchRequest::class => MockResponse::fixture('openai/uses-tools/serper'),
    ]);

    $agent = new OpenAiToolTestAgent;
    $agentResponse = $agent->handle(['input' => 'search google for the current president of the united states.']);

    $agentResponseArray = $agentResponse->toArray();

    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer');
});
