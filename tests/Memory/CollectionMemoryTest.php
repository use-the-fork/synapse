<?php

declare(strict_types=1);

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\ValueObject\SchemaRule;

it('can do a simple query', function (): void {

    class CollectionMemoryAgent extends Agent
    {
        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        public function resolveIntegration(): Integration
        {
            return new OpenAIIntegration;
        }

        protected function registerOutputSchema(): array
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
            return new CollectionMemory;
        }

        protected function registerMemory(): Memory
        {
            return new CollectionMemory;
        }
    }

    $agent = new CollectionMemoryAgent;
    $message = $agent->handle(['query' => 'hello this a test']);

    expect($message)->toBeArray()
        ->and($message)->toHaveKey('answer');
});
