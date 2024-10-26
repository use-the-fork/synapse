<?php

declare(strict_types=1);

    use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Agent;
    use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
    use UseTheFork\Synapse\Contracts\Integration;
    use UseTheFork\Synapse\Contracts\Tool;
    use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
    use UseTheFork\Synapse\Integrations\OpenAIIntegration;
    use UseTheFork\Synapse\Services\Firecrawl\Requests\FirecrawlRequest;
    use UseTheFork\Synapse\Tests\Fixtures\OpenAi\OpenAiFixture;
    use UseTheFork\Synapse\Tools\BaseTool;
    use UseTheFork\Synapse\Tools\Scrape\FirecrawlTool;
    use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
    use UseTheFork\Synapse\ValueObject\SchemaRule;

    test('Firecrawl Tool', function (): void {

    class FirecrawlToolTestAgent extends Agent implements HasOutputSchema
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
            return [new FirecrawlTool];
        }
    }

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): OpenAiFixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));
            return new OpenAiFixture("Tools/FirecrawlTool-{$hash}");
        },
        FirecrawlRequest::class => MockResponse::fixture('Tools/FirecrawlTool-Tool'),
    ]);

    $agent = new FirecrawlToolTestAgent;
    $message = $agent->handle(['input' => 'what is the `https://www.firecrawl.dev/` page about?']);

    $agentResponseArray = $message->toArray();
    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toContain("Firecrawl is a service that provides web scraping and data crawling capabilities focusing on delivering clean, LLM-ready (Large Language Models) data from websites.");

});

test('Architecture', function (): void {

    expect(FirecrawlTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
