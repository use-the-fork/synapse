<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\SystemPrompts;

use UseTheFork\Synapse\SystemPrompts\Contracts\SystemPrompt;

class MultiQueryRetrieverSystemPrompt extends BaseSystemPrompt implements SystemPrompt
{
    public function __construct(
      public int $queryCount = 5
    ) {}

    public function get(): string
    {
        $queryCount = $this->queryCount;
        $expectedOutputFormat = $this->expectedOutputFormat;
        return view('synapse::SystemPrompts.MultiQueryRetrieverSystemPrompt', compact('queryCount', 'expectedOutputFormat'))->render();
    }
}
