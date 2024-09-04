<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Connectors\Contracts;

use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Integrations\ValueObjects\Response;

interface Integration
{
    /**
     * Implement method to fire request.
     *
     * @param  Message[]  $prompt  An array of Message objects
     */
    public function handleCompletion(array $prompt, array $tools = [], array $extraAgentArgs = []): Response;
}
