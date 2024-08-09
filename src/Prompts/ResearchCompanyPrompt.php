<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Prompts;

use UseTheFork\Synapse\Prompts\Contracts\Prompt;

class ResearchCompanyPrompt extends BasePrompt implements Prompt
{
    protected string $bladePrompt = 'synapse::Prompts.ResearchCompanyPrompt';
}
