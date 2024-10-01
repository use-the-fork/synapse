<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents\Traits;

use Illuminate\Support\Facades\Log;
use UseTheFork\Synapse\Agents\PendingAgentTask;
use UseTheFork\Synapse\Enums\PipeOrder;

trait HasLogging
{
    use HasMiddleware;


    /**
     * Logs an event when the thread starts.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task.
     */
    protected function logStartThread(PendingAgentTask $pendingAgentTask): void
    {
        $inputs = $pendingAgentTask->inputs();
        Log::debug("Start Thread with Inputs", $inputs);
    }

    public function bootHasLogging(PendingAgentTask $pendingAgentTask): void
    {
        $this->middleware()->onStartThread(fn(PendingAgentTask $pendingAgentTask) => $this->logStartThread($pendingAgentTask), 'logStartThread', PipeOrder::LAST);
//        $this->middleware()->onEndThread(fn(PendingAgentTask $pendingAgentTask) => $this->doValidateOutputSchema($pendingAgentTask), 'doValidateOutputSchema', PipeOrder::LAST);
    }
}
