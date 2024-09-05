<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools\Contracts;

use Saloon\Http\Connector;

interface Tool
{
    /**
     * Sets the AI integration that this Tool should use when needed.
     */
    public function setIntegration(Connector $agentIntegration): void;

}
