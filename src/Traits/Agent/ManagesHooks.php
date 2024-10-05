<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Agent\HasHooks;
use UseTheFork\Synapse\Enums\PipeOrder;
use UseTheFork\Synapse\Traits\HasMiddleware;

trait ManagesHooks
{
    use HasMiddleware;

    public function bootManagesHooks(): void
    {
        if ($this instanceof HasHooks) {
            $this->middleware()->onStartThread(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookStartThread($pendingAgentTask), 'hookStartThread', PipeOrder::LAST);
            $this->middleware()->onStartIteration(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookStartIteration($pendingAgentTask), 'hookStartIteration', PipeOrder::LAST);

            $this->middleware()->onIntegrationResponse(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookIntegrationResponse($pendingAgentTask), 'hookIntegrationResponse', PipeOrder::LAST);

            $this->middleware()->onStartToolCall(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookStartToolCall($pendingAgentTask), 'hookStartToolCall', PipeOrder::LAST);
            $this->middleware()->onEndToolCall(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookStartToolCall($pendingAgentTask), 'hookEndToolCall', PipeOrder::LAST);

            $this->middleware()->onAgentFinish(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookAgentFinish($pendingAgentTask), 'hookAgentFinish', PipeOrder::LAST);

            $this->middleware()->onEndIteration(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookEndIteration($pendingAgentTask), 'hookEndIteration', PipeOrder::LAST);

            $this->middleware()->onEndThread(fn (PendingAgentTask $pendingAgentTask): \UseTheFork\Synapse\AgentTask\PendingAgentTask => $this->hookEndThread($pendingAgentTask), 'hookEndThread', PipeOrder::LAST);
        }
    }
}
