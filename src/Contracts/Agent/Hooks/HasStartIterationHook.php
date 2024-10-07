<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasStartIterationHook
{
    /**
     * Hook to be executed at the start of an iteration.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task instance.
     * @return PendingAgentTask Returns the pending agent task, possibly modified.
     */
    public function hookStartIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
