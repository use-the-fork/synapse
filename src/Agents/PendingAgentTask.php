<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Agents\StartTasks\BootTraits;
use UseTheFork\Synapse\Agents\StartTasks\MergeProperties;
use UseTheFork\Synapse\Agents\Traits\HasMiddleware;

class PendingAgentTask
{
    use HasMiddleware;

    protected Agent $agent;

    protected Collection $inputs;

    protected CurrentIteration $currentIteration;

    protected Collection $tools;

    public function __construct(Agent $agent, array $inputs, array $extraAgentArgs = [])
    {
        $this->agent = $agent;
        $this->tools = collect();

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

    public function currentIteration(): CurrentIteration|null
    {
        return $this->currentIteration;
    }

    public function tools(): array
    {
        return $this->tools->toArray();
    }

    public function inputs(): array
    {
        return $this->inputs->toArray();
    }

    public function getInput(string $key): mixed
    {
        return $this->inputs[$key];
    }

    public function addInput(string $key,mixed $value): void
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
