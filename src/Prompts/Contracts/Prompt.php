<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Prompts\Contracts;

use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\OutputParsers\Contracts\OutputParser;

interface Prompt
{
    /**
     * Implement method to get message history.
     */
    public function get(array $inputs, OutputParser $outputParser, Memory $memory, array $tools = []): string;

    public function setOutputFormat(string $format): void;
}
