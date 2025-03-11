<?php

declare(strict_types=1);

    use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Agents\SQLToolAgent;
    use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
    use Workbench\App\Models\Organization;

    it('can run the SQL Tool Agent.', function (): void {

    for ($i = 0; $i < 100; $i++) {
        $org = new Organization;
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

    for ($i = 0; $i < 100; $i++) {
        $org = new Organization;
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

    MockClient::global([
        ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
            $count = md5(json_encode($pendingRequest->body()->get('messages')));

            return MockResponse::fixture("Agents/SQLToolAgent-{$count}");
        },
    ]);

    $agent = new SQLToolAgent;
    $message = $agent->handle(['input' => 'How many organizations are operating and what is the average number of funding rounds for them?']);

    $agentResponseArray = $message->toArray();
    expect($agentResponseArray['content'])->toBeArray()
        ->and($agentResponseArray['content'])->toHaveKey('answer')
        ->and($agentResponseArray['content']['answer'])->toContain('100', '5');
})->skip('This test is only for local testing');
