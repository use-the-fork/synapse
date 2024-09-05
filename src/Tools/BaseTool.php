<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Facades\Log;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Useful for getting the contents of a webpage.')]
abstract class BaseTool implements Tool
{
    public function __construct()
    {
        $this->initializeTool();
    }

    /**
     * Initializes the tool.
     */
    protected function initializeTool(): void {}

    /**
     * Logs an event with optional context information.
     *
     * @param  string  $event  The event to be logged.
     * @param  array|null  $context  Optional context information.
     */
    protected function log(string $event, ?array $context = []): void
    {
        $class = get_class($this);
        Log::debug("{$event} in {$class}", $context);
    }
}
