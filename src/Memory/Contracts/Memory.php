<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory\Contracts;

use UseTheFork\Synapse\Integrations\ValueObjects\Message;

interface Memory
{
    /**
     * implement method to add to message.
     */
    public function create(Message $message): void;

    /**
     * Implement method to get message history.
     */
    public function get(): array;

    public function asInputs(): array;

    public function load(): void;
}
