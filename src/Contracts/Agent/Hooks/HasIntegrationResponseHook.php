<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

    use UseTheFork\Synapse\AgentTask\PendingAgentTask;

    interface HasIntegrationResponseHook
    {
        /**
         * Hook into the integration response of a pending agent task.
         *
         * @param PendingAgentTask $pendingAgentTask The pending agent task to hook into.
         *
         * @return PendingAgentTask Returns the pending agent task, possibly modified.
         */
        public function hookIntegrationResponse(PendingAgentTask $pendingAgentTask): PendingAgentTask;
    }
