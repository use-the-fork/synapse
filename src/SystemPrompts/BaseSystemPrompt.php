<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\SystemPrompts;

use UseTheFork\Synapse\SystemPrompts\Contracts\SystemPrompt;

abstract class BaseSystemPrompt implements SystemPrompt
{
    protected string $expectedOutputFormat;

    public function get(): string
    {
        return '';
    }

    public function setOutputFormat(string $format): void
    {
        $this->expectedOutputFormat = $format;
    }
}
