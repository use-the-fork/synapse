<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\SystemPrompts;

use UseTheFork\Synapse\SystemPrompts\Contracts\SystemPrompt;

class SimpleSystemSystemPrompt implements SystemPrompt
{
    public function get(): string
    {
        return 'you are a helpful assistant';
    }
}
