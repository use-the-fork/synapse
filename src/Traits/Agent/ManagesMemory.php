<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Exceptions\MissingResolverException;
use UseTheFork\Synapse\Traits\HasMiddleware;
use UseTheFork\Synapse\ValueObject\Message;

trait ManagesMemory
{

    use HasMiddleware;

    /**
     * The memory that this Model should use
     */
    protected Memory $memory;

    /**
     * Clears the memory of the application.
     *
     * This method clears the memory that is currently stored in the application.
     */
    public function clearMemory(): void
    {
        $this->memory->clear();
    }

    /**
     * Retrieves the memory of the agent
     *
     * @return Memory The memory object of the agent
     */
    public function memory(): Memory
    {
        return $this->memory;
    }

    public function loadMemory(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        $payload = $this->memory->asInputs();

        $pendingAgentTask->addInput('memory', $payload['memory']);
        $pendingAgentTask->addInput('memoryWithMessages', $payload['memoryWithMessages']);

        return $pendingAgentTask;
    }

    /**
     * Adds a message to the current memory
     *
     * @param  PendingAgentTask  $pendingAgentTask  The message to add to the memory.
     */
    public function addMessageToMemory(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        $message = $pendingAgentTask->currentIteration()->getResponse();
        $this->memory->create($message);

        return $pendingAgentTask;
    }

    /**
     * Sets the memory with the given array of messages.
     *
     * @param  array<Message>  $messages  The array of messages to be set in the memory.
     */
    public function setMemory(array $messages): void
    {
        $this->memory->set($messages);
    }

    /**
     * Initializes the memory by registering the memory object.
     * @throws MissingResolverException
     */
    protected function initializeMemory(PendingAgentTask $pendingAgentTask): void
    {
        $this->memory = $this->resolveMemory();
        $this->memory->boot($pendingAgentTask);
    }

    public function bootManagesMemory(PendingAgentTask $pendingAgentTask): void
    {
        $this->middleware()->onBootAgent(fn () => $this->initializeMemory($pendingAgentTask), 'initializeMemory');
        $this->middleware()->onStartIteration(fn () => $this->loadMemory($pendingAgentTask), 'loadMemory');
        $this->middleware()->onEndIteration(fn () => $this->addMessageToMemory($pendingAgentTask), 'memoryEndIteration');
        $this->middleware()->onAgentFinish(fn () => $this->addMessageToMemory($pendingAgentTask), 'memoryAgentFinish');
    }
}
