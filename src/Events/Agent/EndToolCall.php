<?php

namespace UseTheFork\Synapse\Events\Agent;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use UseTheFork\Synapse\AgentTask\PendingAgentTask;

class EndToolCall
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public PendingAgentTask $pendingAgentTask,
    ) {}
}
