<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Prompts;

use UseTheFork\Synapse\Prompts\Contracts\Prompt;

class MultiQueryRetrieverPrompt extends BasePrompt implements Prompt
{
    protected string $bladePrompt = 'synapse::Prompts.MultiQueryRetrieverPrompt';

    public function __construct(
      int $queryCount = 5
    ) {
      $this->extraInputs['queryCount'] = $queryCount;
    }
}
