<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\OutputParsers\Contracts;

interface OutputParser
{
    public function invoke($input): mixed;

    public function getOutputFormat(): string;
}
