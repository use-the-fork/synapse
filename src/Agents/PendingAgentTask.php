<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use Exception;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Throwable;
use UseTheFork\Synapse\Agents\StartTasks\BootTraits;
use UseTheFork\Synapse\Agents\StartTasks\MergeProperties;
use UseTheFork\Synapse\Exceptions\UnknownFinishReasonException;
use UseTheFork\Synapse\Integrations\Concerns\HasIntegration;
use UseTheFork\Synapse\Integrations\Enums\ResponseType;
use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Integrations\ValueObjects\Response;
use UseTheFork\Synapse\Memory\Concerns\HasMemory;
use UseTheFork\Synapse\OutputSchema\Concerns\HasOutputSchema;
use UseTheFork\Synapse\Tools\Concerns\HasTools;
use UseTheFork\Synapse\Traits\Agent\HasMiddleware;
use UseTheFork\Synapse\Utilities\Concerns\HasLogging;

class PendingAgentTask
{
    use HasMiddleware;

    protected Agent $agent;

    protected Collection $input;

    public function __construct(Agent $agent, array $input)
    {
        $this->agent = $agent;
        $this->input = collect($input);

        $this
            ->tap(new BootTraits)
            ->tap(new MergeProperties);

        $this->middleware()->executeStartTaskPipeline($this);

    }

    public function getAgent(): Agent
    {
        return $this->agent;
    }

    public function getInput(string $key): mixed
    {
        return $this->input[$key];
    }

    public function addInput(string $key,mixed $value): void
    {
        $this->input[$key] = $value;
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
