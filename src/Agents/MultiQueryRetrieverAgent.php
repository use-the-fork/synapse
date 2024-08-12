<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

class MultiQueryRetrieverAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.MultiQueryRetrieverPrompt';

    protected function registerOutputRules(): array
    {
        return [
            OutputRule::make([
                'name' => 'answer',
                'rules' => 'required|array',
                'description' => 'the array that holds the new queries.',
            ]),
            OutputRule::make([
                'name' => 'answer.*',
                'rules' => 'required|string',
                'description' => 'a new query.',
            ]),
        ];
    }
}
