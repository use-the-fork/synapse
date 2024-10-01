<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;


use UseTheFork\Synapse\Agent\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\OpenAIConnector;
use UseTheFork\Synapse\Traits\hasIntegration;
use UseTheFork\Synapse\Traits\HasMiddleware;

trait UseIntegration
{
    use HasMiddleware;
    use HasIntegration;

    /**
     * The integration that this Model should use
     */
    protected Integration $integration;

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
     * @return Integration The integration object.
     */
    protected function registerIntegration(): Integration
    {
        return new OpenAIConnector;
    }

    public function bootHasIntegration(PendingAgentTask $pendingAgent): void
    {
        $this->middleware()->onStartThread(fn () => $this->initializeIntegration(), 'initializeIntegration');
    }
}
