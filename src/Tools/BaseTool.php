<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\Agent\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Traits\Makeable;

abstract class BaseTool implements Tool
{

    use Makeable;

    protected PendingAgentTask $pendingAgentTask;

    /**
     * Handle the boot lifecycle hook
     */
    public function setPendingAgentTask(PendingAgentTask $pendingAgentTask)
    {
        $this->pendingAgentTask = $pendingAgentTask;
    }

    /**
     * Handle the boot lifecycle hook
     */
    public function execute(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $pendingAgentTask;
    }
}
