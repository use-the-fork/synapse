<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasBootAgentHook
{
    /**
     * Hook into the boot process of the agent.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task to be modified.
     * @return PendingAgentTask Returns the pending agent task, possibly modified.
     */
    public function hookBootAgent(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
