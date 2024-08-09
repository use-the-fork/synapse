<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory\Contracts;


use UseTheFork\Synapse\Integrations\Exceptions\InvalidEnvironmentException;
use UseTheFork\Synapse\ValueObject\MessageValueObject;

interface Memory
{

    /**
     * implement method to add to message.
     *
     */
    public function create(MessageValueObject $message): void;

    /**
     * Implement method to get message history.
     *
     */
    public function get(): array;

    public function load(): void;

}
