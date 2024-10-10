<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class MultiQueryRetrieverAgent extends Agent implements HasOutputSchema
{
    use ValidatesOutputSchema;

    protected string $promptView = 'synapse::Prompts.MultiQueryRetrieverPrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration;
    }

    public function resolveOutputSchema(): array
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
