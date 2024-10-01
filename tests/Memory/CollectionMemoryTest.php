<?php

declare(strict_types=1);

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\OutputSchema\ValueObjects\SchemaRule;

it('can do a simple query', function (): void {

    class CollectionMemoryAgent extends Agent
    {
        protected string $promptView = 'synapse::Prompts.SimplePrompt';

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

        protected function registerMemory(): Memory
        {
            return new CollectionMemory;
        }
    }

    $agent = new CollectionMemoryAgent;
    $agentResponse = $agent->handle(['query' => 'hello this a test']);

    expect($agentResponse)->toBeArray()
        ->and($agentResponse)->toHaveKey('answer');
});
