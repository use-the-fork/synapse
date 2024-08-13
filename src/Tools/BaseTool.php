<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Useful for getting the contents of a webpage.')]
abstract class BaseTool implements Tool
{
    public function __construct()
    {
        $this->initializeTool();
    }

    protected function initializeTool(): void {}
}
