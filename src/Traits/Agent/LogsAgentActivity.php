<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;

use Illuminate\Support\Facades\Log;
use UseTheFork\Synapse\Agent\PendingAgentTask;
use UseTheFork\Synapse\Enums\PipeOrder;
use UseTheFork\Synapse\Traits\HasMiddleware;

trait LogsAgentActivity
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
        Log::debug('Start Thread with Inputs', $inputs);
    }

    /**
     * Logs an event when the thread starts.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task.
     */
    protected function logIntegrationResponse(PendingAgentTask $pendingAgentTask): void
    {
        Log::debug("Finished Integration with {$pendingAgentTask->currentIteration()->finishReason()->value}");
    }

    protected function logStartToolCall(PendingAgentTask $pendingAgentTask): void
    {
        $currentMessage = $pendingAgentTask->currentIteration()->getResponse()->toArray();
        Log::debug("Entering Tool Call: {$currentMessage['tool_name']}", $currentMessage);
    }

    protected function logEndToolCall(PendingAgentTask $pendingAgentTask): void
    {
        $currentMessage = $pendingAgentTask->currentIteration()->getResponse()->toArray();
        Log::debug("Finished Tool Call: {$currentMessage['tool_name']}", $currentMessage);
    }

    protected function logStartIteration(PendingAgentTask $pendingAgentTask): void
    {
        $inputs = $pendingAgentTask->inputs();
        $iterationCount = $pendingAgentTask->currentIteration()->getIterationCount();
        Log::debug("Start Iteration {$iterationCount}", $inputs);
    }

    protected function logEndIteration(PendingAgentTask $pendingAgentTask): void
    {

        $currentMessage = $pendingAgentTask->currentIteration()->getResponse();
        if ($currentMessage) {
            $iterationCount = $pendingAgentTask->currentIteration()->getIterationCount();
            Log::debug("End Iteration {$iterationCount}", $currentMessage->toArray());
        }
    }

    protected function logAgentFinish(PendingAgentTask $pendingAgentTask): void
    {
        $currentMessage = $pendingAgentTask->currentIteration()->getResponse()->toArray();
        Log::debug('Agent Finished', $currentMessage);
    }

    protected function logEndThread(PendingAgentTask $pendingAgentTask): void
    {
        $inputs = $pendingAgentTask->inputs();
        $currentMessage = $pendingAgentTask->currentIteration()->getResponse()->toArray();

        Log::debug('End Thread', [
            'inputs' => $inputs,
            'message' => $currentMessage,
        ]);
    }

    public function bootLogsAgentActivity(): void
    {
        $this->middleware()->onStartThread(fn (PendingAgentTask $pendingAgentTask) => $this->logStartThread($pendingAgentTask), 'logStartThread', PipeOrder::LAST);
        $this->middleware()->onStartIteration(fn (PendingAgentTask $pendingAgentTask) => $this->logStartIteration($pendingAgentTask), 'logStartIteration', PipeOrder::LAST);

        $this->middleware()->onIntegrationResponse(fn (PendingAgentTask $pendingAgentTask) => $this->logIntegrationResponse($pendingAgentTask), 'logIntegrationResponse', PipeOrder::LAST);

        $this->middleware()->onStartToolCall(fn (PendingAgentTask $pendingAgentTask) => $this->logStartToolCall($pendingAgentTask), 'logStartToolCall', PipeOrder::LAST);
        $this->middleware()->onEndToolCall(fn (PendingAgentTask $pendingAgentTask) => $this->logStartToolCall($pendingAgentTask), 'logEndToolCall', PipeOrder::LAST);

        $this->middleware()->onAgentFinish(fn (PendingAgentTask $pendingAgentTask) => $this->logAgentFinish($pendingAgentTask), 'logAgentFinish', PipeOrder::LAST);

        $this->middleware()->onEndIteration(fn (PendingAgentTask $pendingAgentTask) => $this->logEndIteration($pendingAgentTask), 'logEndIteration', PipeOrder::LAST);

        $this->middleware()->onEndThread(fn (PendingAgentTask $pendingAgentTask) => $this->logEndThread($pendingAgentTask), 'logEndThread', PipeOrder::LAST);

    }
}
