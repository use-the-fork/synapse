<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasAgentFinishHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasBootAgentHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasEndIterationHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasEndThreadHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasEndToolCallHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasIntegrationResponseHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasPromptGeneratedHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasPromptParsedHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasStartIterationHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasStartThreadHook;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasStartToolCallHook;
use UseTheFork\Synapse\Enums\PipeOrder;
use UseTheFork\Synapse\Traits\HasMiddleware;

trait ManagesHooks
{
    use HasMiddleware;

    public function bootManagesHooks(): void
    {

        if ($this instanceof HasBootAgentHook) {
            $this->middleware()->onBootAgent(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookBootAgent($pendingAgentTask), 'hookBootAgent', PipeOrder::LAST);
        }

        if ($this instanceof HasStartThreadHook) {
            $this->middleware()->onStartThread(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookStartThread($pendingAgentTask), 'hookStartThread', PipeOrder::LAST);
        }

        if ($this instanceof HasStartIterationHook) {
            $this->middleware()->onStartIteration(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookStartIteration($pendingAgentTask), 'hookStartIteration', PipeOrder::LAST);
        }

        if ($this instanceof HasPromptGeneratedHook) {
            $this->middleware()->onPromptGenerated(fn (string $generatedPrompt): string => $this->hookPromptGenerated($generatedPrompt), 'hookPromptGenerated', PipeOrder::LAST);
        }

        if ($this instanceof HasPromptParsedHook) {
            $this->middleware()->onPromptParsed(fn (array $parsedPrompt): array => $this->hookPromptParsed($parsedPrompt), 'hookPromptParsed', PipeOrder::LAST);
        }

        if ($this instanceof HasIntegrationResponseHook) {
            $this->middleware()->onIntegrationResponse(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookIntegrationResponse($pendingAgentTask), 'hookIntegrationResponse', PipeOrder::LAST);
        }

        if ($this instanceof HasStartToolCallHook) {
            $this->middleware()->onStartToolCall(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookStartToolCall($pendingAgentTask), 'hookStartToolCall', PipeOrder::LAST);
        }

        if ($this instanceof HasEndToolCallHook) {
            $this->middleware()->onEndToolCall(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookEndToolCall($pendingAgentTask), 'hookEndToolCall', PipeOrder::LAST);
        }

        if ($this instanceof HasAgentFinishHook) {
            $this->middleware()->onAgentFinish(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookAgentFinish($pendingAgentTask), 'hookAgentFinish', PipeOrder::LAST);
        }

        if ($this instanceof HasEndIterationHook) {
            $this->middleware()->onEndIteration(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookEndIteration($pendingAgentTask), 'hookEndIteration', PipeOrder::LAST);
        }

        if ($this instanceof HasEndThreadHook) {
            $this->middleware()->onEndThread(fn (PendingAgentTask $pendingAgentTask): PendingAgentTask => $this->hookEndThread($pendingAgentTask), 'hookEndThread', PipeOrder::LAST);
        }

    }
}
