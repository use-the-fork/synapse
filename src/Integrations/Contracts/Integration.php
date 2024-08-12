<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Contracts;


use UseTheFork\Synapse\Integrations\Exceptions\InvalidEnvironmentException;
use UseTheFork\Synapse\Integrations\ValueObjects\MessageValueObject;

interface Integration
{

    /**
     * Implement method to fire request.
     *
     */
//    public function generateRequestBody(array $messages): array;

    /**
     * Implement method to fire request.
     *
     */
    public function createDtoFromResponse($response): MessageValueObject;

    /**
     * Implement method to fire request.
     *
     */
    public function handle(string $prompt, array $tools = []): MessageValueObject;

    /**
     * Implement Environment validation.
     *
     * @throws InvalidEnvironmentException
     */
    public function validateEnvironment(): void;
}
