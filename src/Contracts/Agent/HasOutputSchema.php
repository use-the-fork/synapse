<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent;

interface HasOutputSchema
{
    public function defaultOutputSchema(): array;
}
