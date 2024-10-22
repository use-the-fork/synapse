<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Tool;

interface ScrapeTool
{
    public function handle(
        string $url
    ): string;
}
