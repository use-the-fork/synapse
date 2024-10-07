<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasStartToolCallHook
{
    /**
     * Hook method called when a tool invocation starts.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task before starting the tool call.
     * @return PendingAgentTask Returns the pending agent task, possibly modified.
     */
    public function hookStartToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
