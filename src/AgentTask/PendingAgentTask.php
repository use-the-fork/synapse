<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\AgentTask;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\AgentTask\StartTasks\BootTraits;
use UseTheFork\Synapse\AgentTask\StartTasks\MergeProperties;
use UseTheFork\Synapse\Enums\FinishReason;
use UseTheFork\Synapse\Traits\HasConfig;
use UseTheFork\Synapse\Traits\HasMiddleware;
use UseTheFork\Synapse\ValueObject\Message;

class PendingAgentTask
{
    use HasMiddleware;
    use hasConfig;

    protected Agent $agent;
    protected Collection $inputs;
    protected Message $response;
    protected Collection $toolCalls;

    protected array $tools = [];

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;

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

    /**
     * Adds an input to the inputs.
     *
     * @param string $key   The key under which the input will be stored.
     * @param mixed  $value The value of the input to be stored.
     *
     * @return void
     */
    public function addInput(string $key, mixed $value): void
    {
        $this->inputs[$key] = $value;
    }

    /**
     * Add a tool.
     *
     * @param string $key   The name or identifier for the tool.
     * @param array  $value The tool configuration or metadata.
     *
     * @return void
     */
    public function addTool(string $key, array $value): void
    {
        $this->tools[$key] = $value;
    }

    public function addToolCall(Message $toolCallWithContent): void
    {
        $this->toolCalls->push($toolCallWithContent);
    }

    /**
     * Retrieve the agent associated with the current task.
     *
     * @return Agent The current agent instance.
     */
    public function agent(): Agent
    {
        return $this->agent;
    }

    public function getExtraAgentArgs(): array
    {
        return $this->config()->get('extraAgentArgs');
    }

    public function getFinishReason(): FinishReason
    {
        return FinishReason::from($this->response->finishReason());
    }

    /**
     * Retrieve an input value by its key.
     *
     * @param string $key The key of the input to retrieve.
     *
     * @return mixed The value of the input associated with the specified key.
     */
    public function getInput(string $key): mixed
    {
        return $this->inputs[$key] ?? null;
    }

    public function getPromptChain(): array
    {
        return $this->config()->get('promptChain');
    }

    public function getResponse(): Message
    {
        return $this->response;
    }

    public function setResponse(Message $message): void
    {
        $this->response = $message;
    }

    public function getToolCalls(): array
    {
        return $this->toolCalls->all();
    }

    /**
     * Get all inputs as an array.
     *
     * @return array An array of inputs.
     */
    public function inputs(): array
    {
        return $this->inputs->toArray();
    }

    /**
     * Reboots the pending agent task with the provided inputs and extra arguments.
     *
     * @param array $inputs         The inputs to be used for the task.
     * @param array $extraAgentArgs Additional arguments for the agent.
     *
     * @return void
     */
    public function reboot(array $inputs, array $extraAgentArgs = []): void
    {

        $this->config()->add('extraAgentArgs', $extraAgentArgs);
        $this->inputs = collect($inputs);
        $this->toolCalls = collect();

        $this->middleware()->executeStartThreadPipeline($this);
    }

    public function setPromptChain(array $promptChain): void
    {
        $this->config()->add('promptChain', $promptChain);
    }

    /**
     * Get the list of tools.
     *
     * @return array An array containing the tools.
     */
    public function tools(): array
    {
        return $this->tools;
    }
}
