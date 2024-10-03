<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Helpers;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Enums\PipeOrder;

class MiddlewarePipeline
{
    /**
     * Start Thread Pipeline
     */
    protected Pipeline $startThreadPipeline;

    /**
     * Start Iteration Pipeline (Runs when the Agents `loop` starts)
     */
    protected Pipeline $startIterationPipeline;

    protected Pipeline $integrationResponsePipeline;

    /**
     * Firers before a tool call is made
     */
    protected Pipeline $startToolCallPipeline;

    /**
     * Firers after a tool call
     */
    protected Pipeline $endToolCallPipeline;

    protected Pipeline $agentFinishPipeline;

    /**
     * End Iteration Pipeline (Runs when the Agents `loop` ends.)
     */
    protected Pipeline $endIterationPipeline;

    /**
     * Complete Task Pipeline
     */
    protected Pipeline $endThreadPipeline;

    /**
     * Constructor
     */
    public function __construct()
    {
        // This is layed out in the order in which these pipes will fire
        $this->startThreadPipeline = new Pipeline;

        $this->startIterationPipeline = new Pipeline;

        $this->integrationResponsePipeline = new Pipeline;

        // Tool call hooks here
        $this->startToolCallPipeline = new Pipeline;
        $this->endToolCallPipeline = new Pipeline;

        $this->agentFinishPipeline = new Pipeline;

        $this->endIterationPipeline = new Pipeline;

        $this->endThreadPipeline = new Pipeline;

    }

    public function onStartThread(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->startThreadPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function onIntegrationResponse(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->integrationResponsePipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function onStartToolCall(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->startToolCallPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function onAgentFinish(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->agentFinishPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function onEndToolCall(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->endToolCallPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function onStartIteration(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->startIterationPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function onEndIteration(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->endIterationPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function onEndThread(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
    {
        $this->endThreadPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $order);

        return $this;
    }

    public function executeStartThreadPipeline(PendingAgentTask $pendingAgent): PendingAgentTask
    {
        return $this->startThreadPipeline->process($pendingAgent);
    }

    public function executeIntegrationResponsePipeline(PendingAgentTask $pendingAgent): PendingAgentTask
    {
        return $this->integrationResponsePipeline->process($pendingAgent);
    }

    public function executeStartToolCallPipeline(PendingAgentTask $pendingAgent): PendingAgentTask
    {
        return $this->startToolCallPipeline->process($pendingAgent);
    }

    public function executeEndToolCallPipeline(PendingAgentTask $pendingAgent): PendingAgentTask
    {
        return $this->endToolCallPipeline->process($pendingAgent);
    }

    public function executeAgentFinishPipeline(PendingAgentTask $pendingAgent): PendingAgentTask
    {
        return $this->agentFinishPipeline->process($pendingAgent);
    }

    public function executeStartIterationPipeline(PendingAgentTask $pendingAgent): PendingAgentTask
    {
        return $this->startIterationPipeline->process($pendingAgent);
    }

    public function executeEndIterationPipeline(PendingAgentTask $pendingAgent): PendingAgentTask
    {
        return $this->endIterationPipeline->process($pendingAgent);
    }

    public function executeEndThreadPipeline(PendingAgentTask $pendingAgentResponse): PendingAgentTask
    {
        return $this->endThreadPipeline->process($pendingAgentResponse);
    }

    public function getStartThreadPipeline(): Pipeline
    {
        return $this->startThreadPipeline;
    }

    public function getIntegrationResponsePipeline(): Pipeline
    {
        return $this->integrationResponsePipeline;
    }

    public function getStartToolCallPipeline(): Pipeline
    {
        return $this->startToolCallPipeline;
    }

    public function getEndToolCallPipeline(): Pipeline
    {
        return $this->endToolCallPipeline;
    }

    public function getAgentFinishPipeline(): Pipeline
    {
        return $this->agentFinishPipeline;
    }

    public function getStartIterationPipeline(): Pipeline
    {
        return $this->startIterationPipeline;
    }

    public function getEndIterationPipeline(): Pipeline
    {
        return $this->endIterationPipeline;
    }

    public function getEndThreadPipeline(): Pipeline
    {
        return $this->endThreadPipeline;
    }

    /**
     * Merge in another middleware pipeline.
     *
     * @return $this
     */
    public function merge(MiddlewarePipeline $middlewarePipeline): static
    {
        $starThreadPipes = array_merge(
            $this->getStartThreadPipeline()->getPipes(),
            $middlewarePipeline->getStartThreadPipeline()->getPipes()
        );

        $integrationResponsePipes = array_merge(
            $this->getIntegrationResponsePipeline()->getPipes(),
            $middlewarePipeline->getIntegrationResponsePipeline()->getPipes()
        );

        $startIterationPipes = array_merge(
            $this->getStartIterationPipeline()->getPipes(),
            $middlewarePipeline->getStartIterationPipeline()->getPipes()
        );

        $startToolCallPipes = array_merge(
            $this->getStartToolCallPipeline()->getPipes(),
            $middlewarePipeline->getStartToolCallPipeline()->getPipes()
        );

        $endToolCallPipes = array_merge(
            $this->getEndToolCallPipeline()->getPipes(),
            $middlewarePipeline->getEndToolCallPipeline()->getPipes()
        );

        $agentFinishPipes = array_merge(
            $this->getAgentFinishPipeline()->getPipes(),
            $middlewarePipeline->getAgentFinishPipeline()->getPipes()
        );

        $endIterationPipes = array_merge(
            $this->getEndIterationPipeline()->getPipes(),
            $middlewarePipeline->getEndIterationPipeline()->getPipes()
        );

        $endThreadPipes = array_merge(
            $this->getEndThreadPipeline()->getPipes(),
            $middlewarePipeline->getEndThreadPipeline()->getPipes()
        );

        $this->startThreadPipeline->setPipes($starThreadPipes);

        $this->integrationResponsePipeline->setPipes($integrationResponsePipes);

        $this->startIterationPipeline->setPipes($startIterationPipes);

        $this->startToolCallPipeline->setPipes($startToolCallPipes);
        $this->endToolCallPipeline->setPipes($endToolCallPipes);

        $this->agentFinishPipeline->setPipes($agentFinishPipes);

        $this->endIterationPipeline->setPipes($endIterationPipes);

        $this->endThreadPipeline->setPipes($endThreadPipes);

        return $this;
    }
}
