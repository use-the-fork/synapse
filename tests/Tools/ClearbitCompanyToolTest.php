<?php

    declare(strict_types=1);

    use Saloon\Http\Faking\Fixture;
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
    use UseTheFork\Synapse\Services\Clearbit\Requests\ClearbitCompanyRequest;
    use UseTheFork\Synapse\Tools\BaseTool;
    use UseTheFork\Synapse\Tools\ClearbitCompanyTool;
    use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
    use UseTheFork\Synapse\ValueObject\SchemaRule;

    test('Clearbit Company Tool', function (): void {

        class ClearbitCompanyToolTestAgent extends Agent implements HasOutputSchema
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
                return [new ClearbitCompanyTool];
            }
        }

        MockClient::global([
                               ChatRequest::class => function (PendingRequest $pendingRequest): Fixture {
                                   $hash = md5(json_encode($pendingRequest->body()->get('messages')));

                                   return MockResponse::fixture("Tools/ClearbitCompanyTool-{$hash}");
                               },
                               ClearbitCompanyRequest::class => MockResponse::fixture('Tools/ClearbitCompanyTool-Tool'),
                           ]);

        $agent = new ClearbitCompanyToolTestAgent;
        $message = $agent->handle(['input' => 'Tell me about `openai.com`.']);

        $agentResponseArray = $message->toArray();
        expect($agentResponseArray['content'])->toBeArray()
                                              ->and($agentResponseArray['content'])->toHaveKey('answer')
                                              ->and($agentResponseArray['content']['answer'])->toContain('OpenAI is an AI research company focused on safe and beneficial artificial intelligence for all, prioritizing human values and diversity in technology.');

    });

    test('Architecture', function (): void {

        expect(ClearbitCompanyTool::class)
            ->toExtend(BaseTool::class)
            ->toImplement(Tool::class);

    });
