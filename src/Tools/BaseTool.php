<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Tools\Contracts\Tool;
use UseTheFork\Synapse\Utilities\Concerns\HasLogging;

#[Description('Useful for getting the contents of a webpage.')]
abstract class BaseTool implements Tool
{
    use HasLogging;

    public function __construct()
    {
        $this->initializeTool();
    }

    /**
     * Initializes the tool.
     */
    protected function initializeTool(): void {}

}
