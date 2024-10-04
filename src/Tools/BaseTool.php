<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Tool;

abstract class BaseTool implements Tool
{

    protected PendingAgentTask $pendingAgentTask;

    /**
     * Initializes the tool.
     */
    protected function initializeTool(): void {}

    /**
     * Handle the boot lifecycle hook
     */
    public function boot(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        $this->pendingAgentTask = $pendingAgentTask;
        $this->initializeTool();

        return $pendingAgentTask;

    }
}
