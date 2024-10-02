<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory\Contracts;

use UseTheFork\Synapse\ValueObject\Message;

interface Memory
{
    /**
     * Retrieves the agent's memory as an array of inputs with messages.
     *
     * @return array An array containing the agent's memory as messages and plain text.
     *               The 'memoryWithMessages' key contains the memory with messages.
     *               The 'memory' key contains the memory without messages.
     */
    public function asInputs(): array;

    /**
     * Clears the agent's memory.
     */
    public function clear(): void;

    /**
     * Adds a new message to agent Memory.
     *
     * @param  Message  $message  The message to be created.
     */
    public function create(Message $message): void;

    /**
     * Retrieves the agent's memory as an array.
     *
     * @return array The agent's memory as an array.
     */
    public function get(): array;

    /**
     * Loads the 'messages' from the agent's memory.
     *
     * This method is called before at the top of the Agents `getAnswer` loop.
     */
    public function load(): void;

    /**
     * Sets the agent's memory with the given array of messages.
     *
     * @param  array  $messages  The array of messages.
     */
    public function set(array $messages): void;
}
