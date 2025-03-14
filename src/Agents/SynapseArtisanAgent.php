<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasMemory;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Traits\Agent\ManagesMemory;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class SynapseArtisanAgent extends Agent implements HasOutputSchema, HasMemory
{
    use ValidatesOutputSchema;
    use ManagesMemory;

    protected string $promptView = 'synapse::Prompts.SynapseArtisanPrompt';

    public function resolveMemory(): Memory
    {
        return new CollectionMemory;
    }

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                                 'name' => 'command',
                                 'rules' => 'required|string',
                                 'description' => 'the artisan command to run.',
                             ]),
        ];
    }
}
