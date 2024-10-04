<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\AgentTask;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\AgentTask\StartTasks\BootTraits;
use UseTheFork\Synapse\AgentTask\StartTasks\MergeProperties;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Traits\HasMiddleware;

class PendingAgentTask
{
    public $memory;
    use HasMiddleware;

    protected Agent $agent;

    protected CurrentIteration $currentIteration;

    protected Collection $inputs;

    protected array $tools = [];

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;

        $this->currentIteration = new CurrentIteration;

        $this
            ->tap(new BootTraits)
            ->tap(new MergeProperties);

        $this->middleware()->executeBootAgentPipeline($this);
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

    public function addInput(string $key, mixed $value): void
    {
        $this->inputs[$key] = $value;
    }

    public function addTool(string $key, array $value): void
    {
        $this->tools[$key] = $value;
    }

    public function currentIteration(): ?CurrentIteration
    {
        return $this->currentIteration;
    }

    public function getAgent(): Agent
    {
        return $this->agent;
    }

    public function getInput(string $key): mixed
    {
        return $this->inputs[$key];
    }

    public function inputs(): array
    {
        return $this->inputs->toArray();
    }

    public function memory(): Memory
    {
        return $this->memory;
    }

    public function reboot(array $inputs, array $extraAgentArgs = []): void
    {

        $this->currentIteration = new CurrentIteration;
        $this->currentIteration->setExtraAgentArgs($extraAgentArgs);

        $this->inputs = collect($inputs);

        $this->middleware()->executeStartThreadPipeline($this);

    }

    public function tools(): array
    {
        return $this->tools;
    }
}
