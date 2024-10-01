<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Agents\StartTasks;

    use UseTheFork\Synapse\Agents\PendingAgentTask;

    class MergeProperties
    {
        /**
         * Merge middleware
         */
        public function __invoke(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {

            $agent = $pendingAgentTask->getAgent();

            $agent->middleware()
                  ->merge($agent->middleware());

            return $pendingAgentTask;
        }
    }
