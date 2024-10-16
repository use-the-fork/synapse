<?php

	namespace UseTheFork\Synapse\Console\Commands;

	use Saloon\Http\Faking\MockClient;
    use Saloon\Http\Faking\MockResponse;
    use Saloon\Http\PendingRequest;
    use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;

    test('Yes - Run Command', function () {

        MockClient::global([
           ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
               $hash = md5(json_encode($pendingRequest->body()->all()));
               return MockResponse::fixture("Console/SynapseArtisan-{$hash}");
           },
       ]);

        $this->artisan('synapse:ask')
             ->expectsQuestion('What would you like artisan to do?', 'create a model migration for Flights')
            ->expectsQuestion('Run This Command?', 'yes')
             ->assertExitCode(0);

	});

    test('Cancel - Exit without running command', function () {

        MockClient::global([
           ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
               $hash = md5(json_encode($pendingRequest->body()->all()));
               return MockResponse::fixture("Console/SynapseArtisan-{$hash}");
           },
       ]);

        $this->artisan('synapse:ask')
             ->expectsQuestion('What would you like artisan to do?', 'create a model migration for Flights')
            ->expectsQuestion('Run This Command?', 'cancel')
             ->assertExitCode(1);
	});

    test('Edit - make changes to command before running', function () {

        MockClient::global([
           ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
               $hash = md5(json_encode($pendingRequest->body()->all()));
               return MockResponse::fixture("Console/SynapseArtisan-{$hash}");
           },
       ]);

        $this->artisan('synapse:ask')
             ->expectsQuestion('What would you like artisan to do?', 'create a model migration for Flights')
            ->expectsQuestion('Run This Command?', 'edit')
            ->expectsQuestion('You can edit command here:', 'make:model Flight')
             ->assertExitCode(0);
	});

    test('Revise - Give Feedback for a new result', function () {

        MockClient::global([
           ChatRequest::class => function (PendingRequest $pendingRequest): \Saloon\Http\Faking\Fixture {
               $hash = md5(json_encode($pendingRequest->body()->all()));
               return MockResponse::fixture("Console/SynapseArtisan-{$hash}");
           },
       ]);

        $this->artisan('synapse:ask')
             ->expectsQuestion('What would you like artisan to do?', 'create a model migration for Flights')
            ->expectsQuestion('Run This Command?', 'revise')
            ->expectsQuestion('You can edit command here:', 'make:model Flight')
             ->assertExitCode(0);
	});
