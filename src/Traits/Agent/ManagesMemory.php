<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\ValueObject\Message;

trait ManagesMemory
{
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
    public function getMemory(): Memory
    {
        return $this->memory;
    }

    /**
     * Adds a message to the current memory
     *
     * @param  Message  $message  The message to add to the memory.
     */
    public function addMessageToMemory(Message $message): void
    {
        $this->memory->create($message);
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
     */
    protected function initializeMemory(): void
    {
        $this->memory = $this->defaultMemory();
    }

    /**
     * Registers the memory type.
     *
     * @return Memory The registered memory instance.
     */
    public function defaultMemory(): Memory
    {
        return new CollectionMemory;
    }

    public function bootManagesMemory(PendingAgentTask $pendingAgentTask): void
    {
        $this->middleware()->onStartThread(fn () => $this->initializeMemory(), 'initializeMemory');
    }
}
