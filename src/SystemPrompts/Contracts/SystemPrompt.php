<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\SystemPrompts\Contracts;

interface SystemPrompt
{
    /**
     * Implement method to get message history.
     */
    public function get(): string;

    public function setOutputFormat(string $format): void;
}
