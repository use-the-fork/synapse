<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts;

use UseTheFork\Synapse\Agent\PendingAgentTask;

interface Tool
{
    public function boot(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function execute(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
