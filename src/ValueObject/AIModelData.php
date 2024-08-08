<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Data;

use Spatie\LaravelData\Data;

class AIModelData extends Data
{
    public function __construct(
        public string $name
    ) {}
}
