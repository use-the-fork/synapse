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
    use UseTheFork\Synapse\Tools\BaseTool;
    use UseTheFork\Synapse\Tools\SQL\InfoSQLDatabaseTool;
    use UseTheFork\Synapse\Tools\SQL\ListSQLDatabaseTool;
    use UseTheFork\Synapse\Tools\SQL\QuerySQLDataBaseTool;
    use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
    use UseTheFork\Synapse\ValueObject\SchemaRule;
    use Workbench\App\Models\Organization;

    test('SQL Tool', function (): void {


        for ($i = 0; $i < 100; $i++){
            $org = new Organization();
            $org->fill([
                           'name' => "Foo - {$i}",
                           'domain' => "Foo_{$i}.com",
                           'country_code' => 'USA',
                           'email' => 'foo@bar.com',
                           'city' => 'hartford',
                           'status' => 'operating',
                           'short_description' => 'lorem ipsum',
                           'num_funding_rounds' => 5,
                           'total_funding_usd' => 1000000,
                           'founded_on' => '2024-03-01',
                       ]);
            $org->save();
        }

        for ($i = 0; $i < 100; $i++){
            $org = new Organization();
            $org->fill([
                           'name' => "Baz - {$i}",
                           'domain' => "Baz_{$i}.com",
                           'country_code' => 'USA',
                           'email' => 'baz@bar.com',
                           'city' => 'hartford',
                           'status' => 'closed',
                           'short_description' => 'lorem ipsum',
                           'num_funding_rounds' => 5,
                           'total_funding_usd' => 1000000,
                           'founded_on' => '2024-03-01',
                       ]);
            $org->save();
        }


    class SQLTestAgent extends Agent implements HasOutputSchema
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
            return [
                new ListSQLDatabaseTool,
                new InfoSQLDatabaseTool,
                new QuerySQLDataBaseTool,
            ];
        }
    }


    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): Fixture {
            $hash = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Tools/SQLTestAgent-{$hash}");
        }
    ]);

    $agent = new SQLTestAgent;
    $message = $agent->handle(['input' => 'How many organizations are operating and what is the average number of funding rounds for them?']);

    $agentResponseArray = $message->toArray();
    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toContain('There are 100 organizations operating with an average of 5 funding rounds each.');

});

test('Architecture', function (): void {

    expect(ListSQLDatabaseTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class)
        ->and(InfoSQLDatabaseTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class)
        ->and(QuerySQLDataBaseTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
