<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasAgentFinishHook
{
    /**
     * Hook to be executed when the agent finishes its task.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task instance that is about to finish.
     * @return PendingAgentTask Returns the pending agent task, possibly modified.
     */
    public function hookAgentFinish(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
