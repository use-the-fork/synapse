<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class SimpleAgent extends Agent
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
}
