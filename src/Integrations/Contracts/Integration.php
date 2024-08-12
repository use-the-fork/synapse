<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Contracts;

use UseTheFork\Synapse\Integrations\Exceptions\InvalidEnvironmentException;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;

interface Integration
{
    /**
     * Implement method to fire request.
     */
    //    public function generateRequestBody(array $messages): array;

    /**
     * Implement method to fire request.
     */
    public function createDtoFromResponse($response): Message;

    /**
     * Implement method to fire request.
     *
     * @param  Message[]  $prompt  An array of Message objects
     */
    public function handle(array $prompt, array $tools = []): Message;

    /**
     * Implement Environment validation.
     *
     * @throws InvalidEnvironmentException
     */
    public function validateEnvironment(): void;
}
