<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents\Rat;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class RatSplitAnswerAgent extends Agent
{
    use ValidatesOutputSchema;

    protected string $promptView = 'synapse::Prompts.Rat.SplitAnswerPrompt';

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                                 'name' => 'paragraphs',
                                 'rules' => 'required|array',
                                 'description' => 'Your answer split in to paragraphs.',
                             ]),
            SchemaRule::make([
                                 'name' => 'paragraphs.*',
                                 'rules' => 'required|string',
                                 'description' => 'A paragraph of your answer.',
                             ]),
        ];
    }
}
