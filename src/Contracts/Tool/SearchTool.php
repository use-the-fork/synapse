<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Tool;

interface SearchTool
{
    public function handle(
        string $query,
        ?string $searchType = 'search',
        ?int $numberOfResults = 10
    ): string;
}
