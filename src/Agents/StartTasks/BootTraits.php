<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Agents\StartTasks;


    use UseTheFork\Synapse\Agents\Agent;
    use UseTheFork\Synapse\Agents\PendingAgentTask;
    use UseTheFork\Synapse\Helpers\Helpers;

    class BootTraits
    {
        /**
         * Boot the plugins
         */
        public function __invoke(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {


            $agentTraits = Helpers::classUsesRecursive($pendingAgentTask);

            foreach ($agentTraits as $agentTrait) {
                Helpers::bootPlugin($pendingAgentTask, $agentTrait);
            }

            return $pendingAgentTask;
        }
    }
