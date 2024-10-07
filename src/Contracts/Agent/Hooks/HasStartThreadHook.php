<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasStartThreadHook
{
    public function hookStartThread(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
