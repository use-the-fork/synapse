<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

    use UseTheFork\Synapse\AgentTask\PendingAgentTask;

    interface HasEndThreadHook
    {
        /**
         * This method is invoked at the end of a thread, allowing for any necessary
         * clean-up or finalization tasks to be performed on the provided PendingAgentTask instance.
         *
         * @param PendingAgentTask $pendingAgentTask The pending agent task instance.
         *
         * @return PendingAgentTask Returns the pending agent task, possibly modified.
         */

         public function hookEndThread(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    }
