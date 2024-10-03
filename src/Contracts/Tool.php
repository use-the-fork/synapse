<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface Tool
{
    public function boot(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
