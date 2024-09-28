<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\OutputSchema\ValueObjects\SchemaRule;

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
