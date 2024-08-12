<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory\Concerns;

use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\Memory\DatabaseMemory;

trait HasMemory
{
    /**
     * The integration that this Model should use
     */
    protected Memory $memory;

    /**
     * returns the memory type this Agent should use.
     */
    protected function registerMemory(): Memory
    {
        return new DatabaseMemory();
    }

    /**
     * sets the memory type this agent will use.
     */
    protected function initializeMemory(): void
    {
        $this->memory = $this->registerMemory();
    }
}
