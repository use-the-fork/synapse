<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Concerns;

use Saloon\Http\Connector;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\OpenAIConnector;

trait HasIntegration
{
    /**
     * The integration that this Model should use
     */
    protected Connector $integration;

    /**
     * The chat request class this model uses. A new one is created every time.
     */
    protected string $chatRequestClass;

    /**
     * returns the memory type this Agent should use.
     */
    protected function registerIntegration(): Connector
    {
		return new OpenAIConnector();
    }

    /**
     * Resolve the observe class names from the attributes.
     */
    protected function initializeIntegration(): void
    {
		$this->integration = $this->registerIntegration();
    }

}
