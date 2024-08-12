<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

class ProfessionalEditorAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.ProfessionalEditorPrompt';

    protected function registerOutputRules(): array
    {
      return [
        OutputRule::make([
                                      'name'       => 'meaning',
                                      'rules'       => 'required|string',
                                      'description' => 'a quick summary of the key points of the original text.'
                                    ])
      ];
    }
}
