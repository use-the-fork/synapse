<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;
use UseTheFork\Synapse\Tools\FirecrawlTool;

class SimpleAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    protected function registerOutputRules(): array
    {
        return [
            OutputRule::make([
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
