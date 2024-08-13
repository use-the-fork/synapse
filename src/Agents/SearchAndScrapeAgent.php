<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;
use UseTheFork\Synapse\Tools\FirecrawlTool;
use UseTheFork\Synapse\Tools\SerperTool;

class SearchAndScrapeAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.SearchAndScrapeAgent';

    protected function registerOutputRules(): array
    {
        return [
            OutputRule::make([
                'name' => 'final_answer',
                'rules' => 'required|string',
                'description' => 'A markdown formated answer to the users query.',
            ]),
        ];
    }

    protected function registerTools(): array
    {
        return [
            new SerperTool(env('SERPER_API_KEY')),
            new FirecrawlTool(env('FIRECRAWL_API_KEY')),
        ];
    }
}
