<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

    use UseTheFork\Synapse\AgentTask\PendingAgentTask;

    interface HasEndToolCallHook
    {
        /**
         * Hook that gets executed at the end of a tool call.
         *
         * @param PendingAgentTask $pendingAgentTask The pending agent task instance.
         *
         * @return PendingAgentTask Returns the pending agent task, possibly modified.
         */
        public function hookEndToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask;
    }
