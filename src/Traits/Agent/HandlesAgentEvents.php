<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Enums\PipeOrder;
use UseTheFork\Synapse\Events\Agent\AgentFinish;
use UseTheFork\Synapse\Events\Agent\BootAgent;
use UseTheFork\Synapse\Events\Agent\EndIteration;
use UseTheFork\Synapse\Events\Agent\EndThread;
use UseTheFork\Synapse\Events\Agent\EndToolCall;
use UseTheFork\Synapse\Events\Agent\IntegrationResponse;
use UseTheFork\Synapse\Events\Agent\PromptGenerated;
use UseTheFork\Synapse\Events\Agent\PromptParsed;
use UseTheFork\Synapse\Events\Agent\StartIteration;
use UseTheFork\Synapse\Events\Agent\StartThread;
use UseTheFork\Synapse\Events\Agent\StartToolCall;
use UseTheFork\Synapse\Traits\HasMiddleware;
use UseTheFork\Synapse\ValueObject\Message;

trait HandlesAgentEvents
{
    use HasMiddleware;

    public function bootHandlesAgentEvents(): void
    {
        $this->middleware()->onBootAgent(fn (PendingAgentTask $pendingAgentTask) => $this->eventBootAgent($pendingAgentTask), 'eventBootAgent', PipeOrder::LAST);

        $this->middleware()->onStartThread(fn (PendingAgentTask $pendingAgentTask) => $this->eventStartThread($pendingAgentTask), 'eventStartThread', PipeOrder::LAST);
        $this->middleware()->onStartIteration(fn (PendingAgentTask $pendingAgentTask) => $this->eventStartIteration($pendingAgentTask), 'eventStartIteration', PipeOrder::LAST);

        $this->middleware()->onPromptGenerated(fn (string $generatedPrompt) => $this->eventPromptGenerated($generatedPrompt), 'eventPromptGenerated', PipeOrder::LAST);
        $this->middleware()->onPromptParsed(fn (array $parsedPrompt) => $this->eventPromptParsed($parsedPrompt), 'eventPromptParsed', PipeOrder::LAST);

        $this->middleware()->onIntegrationResponse(fn (PendingAgentTask $pendingAgentTask) => $this->eventIntegrationResponse($pendingAgentTask), 'eventIntegrationResponse', PipeOrder::LAST);

        $this->middleware()->onStartToolCall(fn (PendingAgentTask $pendingAgentTask) => $this->eventStartToolCall($pendingAgentTask), 'eventStartToolCall', PipeOrder::LAST);
        $this->middleware()->onEndToolCall(fn (PendingAgentTask $pendingAgentTask) => $this->eventEndToolCall($pendingAgentTask), 'eventEndToolCall', PipeOrder::LAST);

        $this->middleware()->onAgentFinish(fn (PendingAgentTask $pendingAgentTask) => $this->eventAgentFinish($pendingAgentTask), 'eventAgentFinish', PipeOrder::LAST);

        $this->middleware()->onEndIteration(fn (PendingAgentTask $pendingAgentTask) => $this->eventEndIteration($pendingAgentTask), 'eventEndIteration', PipeOrder::LAST);

        $this->middleware()->onEndThread(fn (PendingAgentTask $pendingAgentTask) => $this->eventEndThread($pendingAgentTask), 'eventEndThread', PipeOrder::LAST);

    }

    protected function eventBootAgent(PendingAgentTask $pendingAgentTask): void
    {
        BootAgent::dispatch($pendingAgentTask);
    }

    /**
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task.
     */
    protected function eventStartThread(PendingAgentTask $pendingAgentTask): void
    {
        StartThread::dispatch($pendingAgentTask);
    }

    protected function eventStartIteration(PendingAgentTask $pendingAgentTask): void
    {
        StartIteration::dispatch($pendingAgentTask);
    }

    protected function eventPromptGenerated(string $generatedPrompt): void
    {
        PromptGenerated::dispatch($generatedPrompt);
    }

    /**
     * Handle the event when a prompt is parsed.
     *
     * @param  array<Message>  $parsedPrompt  The parsed prompt array.
     */
    protected function eventPromptParsed(array $parsedPrompt): void
    {
        PromptParsed::dispatch($parsedPrompt);
    }

    /**
     * @param  PendingAgentTask  $pendingAgentTask  The pending agent task.
     */
    protected function eventIntegrationResponse(PendingAgentTask $pendingAgentTask): void
    {
        IntegrationResponse::dispatch($pendingAgentTask);
    }

    protected function eventStartToolCall(PendingAgentTask $pendingAgentTask): void
    {
        StartToolCall::dispatch($pendingAgentTask);
    }

    protected function eventEndToolCall(PendingAgentTask $pendingAgentTask): void
    {
        EndToolCall::dispatch($pendingAgentTask);
    }

    protected function eventAgentFinish(PendingAgentTask $pendingAgentTask): void
    {
        AgentFinish::dispatch($pendingAgentTask);
    }

    protected function eventEndIteration(PendingAgentTask $pendingAgentTask): void
    {
        EndIteration::dispatch($pendingAgentTask);
    }

    protected function eventEndThread(PendingAgentTask $pendingAgentTask): void
    {
        EndThread::dispatch($pendingAgentTask);
    }
}
