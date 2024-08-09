<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\SystemPrompts;

use UseTheFork\Synapse\SystemPrompts\Contracts\SystemPrompt;

class ResearchCompanySystemPrompt extends BaseSystemPrompt implements SystemPrompt
{
    public function __construct(
      public array $tools = []
    ) {}

    public function get(): string
    {
        $tools = $this->tools;
        $expectedOutputFormat = $this->expectedOutputFormat;
        return view('synapse::SystemPrompts.ResearchCompanySystemPrompt', compact('tools', 'expectedOutputFormat'))->render();
    }
}
