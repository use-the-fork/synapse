<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\Agent\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Traits\Agent\LogsAgentActivity;

abstract class BaseTool implements Tool
{
    use LogsAgentActivity;

    protected PendingAgentTask $pendingAgentTask;

    /**
     * Initializes the tool.
     */
    protected function initializeTool(): void {}

    /**
     * Handle the boot lifecycle hook
     */
    public function boot(PendingAgentTask $pendingAgentTask): void
    {
        $this->pendingAgentTask = $pendingAgentTask;
        $this->initializeTool();
    }
}
