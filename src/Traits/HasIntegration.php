<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits;

use UseTheFork\Synapse\Contracts\Integration;

trait HasIntegration
{
    protected Integration $integration;

    public function integration(): Integration
    {
        return $this->integration;
    }
}
