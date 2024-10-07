<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

    use UseTheFork\Synapse\AgentTask\PendingAgentTask;

    interface HasStartIterationHook
    {
        public function hookStartIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask;
    }
