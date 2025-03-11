<?php

declare(strict_types=1);

    use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Agent;
    use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
    use UseTheFork\Synapse\Contracts\Integration;
    use UseTheFork\Synapse\Contracts\Memory;
    use UseTheFork\Synapse\Contracts\Tool;
    use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
    use UseTheFork\Synapse\Integrations\OpenAIIntegration;
    use UseTheFork\Synapse\Memory\CollectionMemory;
    use UseTheFork\Synapse\Services\Crunchbase\Requests\CrunchbaseRequest;
    use UseTheFork\Synapse\Tests\Fixtures\OpenAi\OpenAiFixture;
    use UseTheFork\Synapse\Tools\BaseTool;
    use UseTheFork\Synapse\Tools\CrunchbaseTool;
    use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
    use UseTheFork\Synapse\ValueObject\SchemaRule;

    test('Crunchbase Tool', function (): void {

    class CrunchbaseToolTestAgent extends Agent implements HasOutputSchema
    {
        use ValidatesOutputSchema;

        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
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
            return [new CrunchbaseTool];
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): OpenAiFixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));
            return new OpenAiFixture("Tools/CrunchbaseTool-{$hash}");
        },
        CrunchbaseRequest::class => MockResponse::fixture('Tools/CrunchbaseTool-Tool'),
    ]);

    $agent = new CrunchbaseToolTestAgent;
    $message = $agent->handle(['input' => 'Tell me about entityId `siteimprove`.']);

    $agentResponseArray = $message->toArray();
    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toContain('Siteimprove is a company that provides comprehensive cloud-based digital presence optimization software.');

});

test('Architecture', function (): void {

    expect(CrunchbaseTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
