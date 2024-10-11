<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class KnowledgeGraphExtractionAgent extends Agent implements HasOutputSchema
{
    use ValidatesOutputSchema;

    // Credits to https://github.com/tomasonjo/blogs/blob/master/llm/openaifunction_constructing_graph.ipynb
    protected string $promptView = 'synapse::Prompts.KnowledgeGraphExtractionPrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration;
    }

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'nodes',
                'rules' => 'required|array',
                'description' => 'a .',
            ]),
            SchemaRule::make([
                'name' => 'relationships',
                'rules' => 'required|array',
                'description' => 'the relationships that have been identified',
            ]),
            SchemaRule::make([
                'name' => 'relationships.*.source',
                'rules' => 'required|array',
                'description' => 'the relationships that have been identified',
            ]),
        ];
    }
}
