<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent;

use UseTheFork\Synapse\Contracts\Integration;

interface HasIntegration
{
    public function resolveIntegration(): Integration;
}
