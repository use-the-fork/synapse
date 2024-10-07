<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Enums\PipeOrder;
use UseTheFork\Synapse\ValueObject\Message;

class MiddlewarePipeline
{
    protected Pipeline $agentFinishPipeline;

    protected Pipeline $bootAgentPipeline;

    protected Pipeline $endIterationPipeline;

    protected Pipeline $endThreadPipeline;

    protected Pipeline $endToolCallPipeline;

    protected Pipeline $integrationResponsePipeline;

    protected Pipeline $promptGeneratedPipeline;

    protected Pipeline $promptParsedPipeline;

    protected Pipeline $startIterationPipeline;

    protected Pipeline $startThreadPipeline;

    protected Pipeline $startToolCallPipeline;

    /**
     * Constructor
     */
    public function __construct()
    {
        # 1: This executes one time when the agent is constructed
        $this->bootAgentPipeline = new Pipeline;

        # 2: This executes everytime a handle call is made and internal settings are reset.
        $this->startThreadPipeline = new Pipeline;

        # 3: This executes after a prompt is generated and before it is parsed.
        $this->startIterationPipeline = new Pipeline;

        # 4: This executes after a prompt is generated and before it is parsed.
        $this->promptGeneratedPipeline = new Pipeline;

        # 5: This executes after a prompt is parsed and before it is set and sent to the integration.
        $this->promptParsedPipeline = new Pipeline;

        # 6: This executes when the integration responds, and before it's processed.
        $this->integrationResponsePipeline = new Pipeline;

        # 6a: This executes before a tool call.
        $this->startToolCallPipeline = new Pipeline;

        # 6b: This executes after a tool call is completed.
        $this->endToolCallPipeline = new Pipeline;

        # 7a: This executes when an iteration ends and a Stop response was not reached.
        $this->endIterationPipeline = new Pipeline;

        # 7b: This executes when an iteration ends and a Stop response is reached.
        $this->agentFinishPipeline = new Pipeline;

        # 8: This executes before the handle function responds.
        $this->endThreadPipeline = new Pipeline;

    }

    public function executeAgentFinishPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->agentFinishPipeline->process($pendingAgentTask);
    }

    public function executeBootAgentPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->bootAgentPipeline->process($pendingAgentTask);
    }

    public function executeEndIterationPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->endIterationPipeline->process($pendingAgentTask);
    }

    public function executeEndThreadPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->endThreadPipeline->process($pendingAgentTask);
    }

    public function executeEndToolCallPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->endToolCallPipeline->process($pendingAgentTask);
    }

    public function executeIntegrationResponsePipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->integrationResponsePipeline->process($pendingAgentTask);
    }

    public function executePromptGeneratedPipeline(string $generatedPrompt): string
    {
        return $this->promptGeneratedPipeline->process($generatedPrompt);
    }

    /**
     * Executes the promptParsedPipeline process.
     *
     * @param array<Message> $parsedMessages The parsed messages.
     *
     * @return array<Message> The parsed messages.
     */
    public function executePromptParsedPipeline(array $parsedMessages): array
    {
        return $this->promptParsedPipeline->process($parsedMessages);
    }

    public function executeStartIterationPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->startIterationPipeline->process($pendingAgentTask);
    }

    public function executeStartThreadPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->startThreadPipeline->process($pendingAgentTask);
    }

    public function executeStartToolCallPipeline(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        return $this->startToolCallPipeline->process($pendingAgentTask);
    }

    /**
     * Merge in another middleware pipeline.
     *
     * @return $this
     */
    public function merge(MiddlewarePipeline $middlewarePipeline): static
    {
        $bootAgentPipes = array_merge(
            $this->getBootAgentPipeline()->getPipes(),
            $middlewarePipeline->getBootAgentPipeline()->getPipes()
        );

        $startThreadPipes = array_merge(
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

        $promptParsedPipes = array_merge(
            $this->getPromptParsedPipeline()->getPipes(),
            $middlewarePipeline->getPromptParsedPipeline()->getPipes()
        );

        $promptGeneratedPipes = array_merge(
            $this->getPromptGeneratedPipeline()->getPipes(),
            $middlewarePipeline->getPromptGeneratedPipeline()->getPipes()
        );

        $this->promptGeneratedPipeline->setPipes($promptGeneratedPipes);

        $this->promptParsedPipeline->setPipes($promptParsedPipes);

        $this->bootAgentPipeline->setPipes($bootAgentPipes);

        $this->startThreadPipeline->setPipes($startThreadPipes);

        $this->integrationResponsePipeline->setPipes($integrationResponsePipes);

        $this->startIterationPipeline->setPipes($startIterationPipes);

        $this->startToolCallPipeline->setPipes($startToolCallPipes);
        $this->endToolCallPipeline->setPipes($endToolCallPipes);

        $this->agentFinishPipeline->setPipes($agentFinishPipes);

        $this->endIterationPipeline->setPipes($endIterationPipes);

        $this->endThreadPipeline->setPipes($endThreadPipes);

        return $this;
    }

    public function getBootAgentPipeline(): Pipeline
    {
        return $this->bootAgentPipeline;
    }

    public function getStartThreadPipeline(): Pipeline
    {
        return $this->startThreadPipeline;
    }

    public function getIntegrationResponsePipeline(): Pipeline
    {
        return $this->integrationResponsePipeline;
    }

    public function getStartIterationPipeline(): Pipeline
    {
        return $this->startIterationPipeline;
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

    public function getEndIterationPipeline(): Pipeline
    {
        return $this->endIterationPipeline;
    }

    public function getEndThreadPipeline(): Pipeline
    {
        return $this->endThreadPipeline;
    }

    public function getPromptParsedPipeline(): Pipeline
    {
        return $this->promptParsedPipeline;
    }

    public function getPromptGeneratedPipeline(): Pipeline
    {
        return $this->promptGeneratedPipeline;
    }

    public function onAgentFinish(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->agentFinishPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onBootAgent(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->bootAgentPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onEndIteration(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->endIterationPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onEndThread(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->endThreadPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onEndToolCall(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->endToolCallPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onIntegrationResponse(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->integrationResponsePipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onPromptGenerated(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->promptGeneratedPipeline->pipe(static function (string $generatedPrompt) use ($callable): string {
            $result = $callable($generatedPrompt);

            if ($result instanceof \Stringable) {
                return (string) $result;
            }

            return $generatedPrompt;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onPromptParsed(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->promptParsedPipeline->pipe(static function (array $parsedPrompt) use ($callable): array {
            $result = $callable($parsedPrompt);

            if ($result instanceof Arrayable) {
                return (array) $result;
            }

            return $parsedPrompt;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onStartIteration(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->startIterationPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onStartThread(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->startThreadPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }

    public function onStartToolCall(callable $callable, ?string $name = null, ?PipeOrder $pipeOrder = null): static
    {
        $this->startToolCallPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
            $result = $callable($pendingAgentTask);

            if ($result instanceof PendingAgentTask) {
                return $result;
            }

            return $pendingAgentTask;
        }, $name, $pipeOrder);

        return $this;
    }
}
