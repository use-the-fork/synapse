<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\AgentTask;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class ChatRephraseAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.ChatRephrasePrompt';

    protected function registerOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'standalone_question',
                'rules' => 'required|string',
                'description' => 'The standalone question',
            ]),
        ];
    }
}
