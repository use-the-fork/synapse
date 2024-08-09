<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Prompts;

use UseTheFork\Synapse\Prompts\Contracts\Prompt;

class SimplePrompt extends BasePrompt implements Prompt
{
    protected string $bladePrompt = 'synapse::Prompts.SimplePrompt';
}
