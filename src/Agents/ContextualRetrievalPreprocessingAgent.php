<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class ContextualRetrievalPreprocessingAgent extends Agent implements HasOutputSchema
{
    use ValidatesOutputSchema;

    // Credits to https://www.anthropic.com/news/contextual-retrieval
    protected string $promptView = 'synapse::Prompts.ContextualRetrievalPreprocessingPrompt';

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'succinct_context',
                'rules' => 'required|string',
                'description' => 'the succinct context string.',
            ]),
        ];
    }
}
