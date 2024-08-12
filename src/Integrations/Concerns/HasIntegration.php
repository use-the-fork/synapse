<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Concerns;

use UseTheFork\Synapse\Integrations\Contracts\Integration;
use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;

trait HasIntegration
{
    /**
     * The integration that this Model should use
     */
    protected Integration $integration;

    /**
     * returns the memory type this Agent should use.
     */
    protected function registerIntegration(): Integration
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
