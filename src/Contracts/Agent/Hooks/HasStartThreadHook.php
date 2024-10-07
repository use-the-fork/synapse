<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasStartThreadHook
{
    /**
     * Hook into the start of a thread for the given pending agent task.
     *
     * @param PendingAgentTask $pendingAgentTask The pending agent task to hook into.
     *
     * @return PendingAgentTask Returns the pending agent task, possibly modified.
     */
    public function hookStartThread(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
