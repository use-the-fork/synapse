<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Templates;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class MultiQueryRetrieverAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.MultiQueryRetrieverPrompt';

    protected function registerOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'answer',
                'rules' => 'required|array',
                'description' => 'the array that holds the new queries.',
            ]),
            SchemaRule::make([
                'name' => 'answer.*',
                'rules' => 'required|string',
                'description' => 'a new query.',
            ]),
        ];
    }
}
