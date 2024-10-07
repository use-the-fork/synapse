<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasEndIterationHook
{

    /**
     * Hook to be called at the end of each iteration.
     *
     * @param PendingAgentTask $pendingAgentTask The pending agent task instance.
     *
     * @return PendingAgentTask Returns the pending agent task, possibly modified.
     */
    public function hookEndIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
