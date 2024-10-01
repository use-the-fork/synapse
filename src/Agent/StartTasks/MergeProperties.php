<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agent\StartTasks;

use UseTheFork\Synapse\Agent\PendingAgentTask;

class MergeProperties
{
    /**
     * Merge middleware
     */
    public function __invoke(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {

        $agent = $pendingAgentTask->getAgent();

        $pendingAgentTask->middleware()
            ->merge($agent->middleware());

        return $pendingAgentTask;
    }
}
