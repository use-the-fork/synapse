<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\AgentTask\StartTasks;


    use UseTheFork\Synapse\AgentTask\PendingAgentTask;
    use UseTheFork\Synapse\Helpers\Helpers;

    class BootTraits
    {
        /**
         * Boot the plugins
         */
        public function __invoke(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {

            $agent = $pendingAgentTask->agent();
            $agentTraits = Helpers::classUsesRecursive($agent);

            foreach ($agentTraits as $agentTrait) {
                Helpers::bootPlugin($pendingAgentTask, $agentTrait);
            }

            return $pendingAgentTask;
        }
    }
