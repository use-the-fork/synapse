<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Concerns;

use Saloon\Http\Connector;
use UseTheFork\Synapse\Agents\PendingAgentTask;
use UseTheFork\Synapse\Agents\Traits\HasMiddleware;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\OpenAIConnector;

trait HasIntegration
{
    use HasMiddleware;

    /**
     * The integration that this Model should use
     */
    protected Connector $integration;

    /**
     * Initializes the integration by registering it.
     *
     * This method assigns the integration object returned by the `registerIntegration` method
     * to the `$integration` property of the class.
     */
    public function initializeIntegration(): void
    {
        $this->integration = $this->registerIntegration();
    }

    /**
     * Registers the integration and returns the integration object.
     *
     * This method creates a new instance of a `Integration` class and
     * returns it as the integration object.
     *
     * @return Connector The integration object.
     */
    protected function registerIntegration(): Connector
    {
        return new OpenAIConnector;
    }

    public function bootHasIntegration(PendingAgentTask $pendingAgent): void
    {
        $this->middleware()->onStartThread(fn () => $this->initializeIntegration(), 'initializeIntegration');
    }
}
