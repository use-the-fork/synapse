<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Saloon\Http\Connector;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Traits\Agent\UseLogging;

#[Description('Useful for getting the contents of a webpage.')]
abstract class BaseTool implements Tool
{
    use UseLogging;

    /**
     * The AI integration that this Tool should use when needed.
     */
    protected Connector $integration;

    public function __construct()
    {
        $this->initializeTool();
    }

    /**
     * Initializes the tool.
     */
    protected function initializeTool(): void {}

    /**
     * Sets the AI integration that this Tool should use when needed.
     */
    public function setIntegration(Connector $connector): void
    {
        $this->integration = $connector;
    }
}
