<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents\Rat;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class GetQueryAgent extends Agent
{
    use ValidatesOutputSchema;

    protected string $promptView = 'synapse::Prompts.Rat.GetQueryPrompt';

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'query',
                'rules' => 'required|string',
                'description' => 'The query to search for.',
            ]),
        ];
    }
}
