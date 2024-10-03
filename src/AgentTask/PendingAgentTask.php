<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\AgentTask;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\AgentTask;
use UseTheFork\Synapse\AgentTask\StartTasks\BootTraits;
use UseTheFork\Synapse\AgentTask\StartTasks\MergeProperties;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Traits\HasMiddleware;

class PendingAgentTask
{
    use HasMiddleware;

    protected Agent $agent;

    protected Collection $inputs;

    protected CurrentIteration $currentIteration;

    protected Memory $iterationMemory;

    protected array $tools = [];

    public function __construct(Agent $agent, array $inputs, array $extraAgentArgs = [])
    {
        $this->agent = $agent;

        $this->iterationMemory = new CollectionMemory();
        $this->iterationMemory->boot();

        $this->currentIteration = new CurrentIteration;
        $this->currentIteration->setExtraAgentArgs($extraAgentArgs);

        $this->inputs = collect($inputs);

        $this
            ->tap(new BootTraits)
            ->tap(new MergeProperties);

        $this->middleware()->executeStartThreadPipeline($this);

    }

    public function getAgent(): Agent
    {
        return $this->agent;
    }

    public function currentIteration(): ?CurrentIteration
    {
        return $this->currentIteration;
    }

    public function addTool(string $key, array $value): void
    {
        $this->tools[$key] = $value;
    }

    public function iterationMemory(): Memory
    {
        return $this->iterationMemory;
    }

    public function getIterationMemory(): string
    {
        $inputs = $this->iterationMemory->asInputs();
        return $inputs['memoryWithMessages'];
    }

    public function tools(): array
    {
        return $this->tools;
    }

    public function inputs(): array
    {
        return $this->inputs->toArray();
    }

    public function getInput(string $key): mixed
    {
        return $this->inputs[$key];
    }

    public function addInput(string $key, mixed $value): void
    {
        $this->inputs[$key] = $value;
    }

    /**
     * Tap into the agent
     *
     * @return $this
     */
    protected function tap(callable $callable): static
    {
        $callable($this);

        return $this;
    }
}
