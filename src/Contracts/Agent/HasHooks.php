<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;

interface HasHooks
{
    public function hookAgentFinish(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookEndIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookEndThread(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookEndToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookIntegrationResponse(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookStartIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookStartThread(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookStartToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask;
}
