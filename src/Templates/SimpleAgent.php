<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Templates;

use UseTheFork\Synapse\AgentTask;
use UseTheFork\Synapse\Tools\FirecrawlTool;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class SimpleAgent extends Agent
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

    protected function registerTools(): array
    {
        return [
            new FirecrawlTool(env('FIRECRAWL_API_KEY')),
        ];
    }
}
