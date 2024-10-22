<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Tool;

use UseTheFork\Synapse\Enums\ReturnType;

interface SearchTool
{
    public function handle(
        string $query,
        ?string $searchType = 'search',
        ?int $numberOfResults = 10,
        ReturnType $returnType = ReturnType::STRING,
    ): string|array;
}
