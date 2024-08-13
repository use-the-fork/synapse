<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

class ChatRephraseAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.ChatRephrasePrompt';

    protected function registerOutputRules(): array
    {
        return [
            OutputRule::make([
                'name' => 'standalone_question',
                'rules' => 'required|string',
                'description' => 'The standalone question',
            ]),
        ];
    }
}
