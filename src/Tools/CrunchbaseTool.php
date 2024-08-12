<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\ClearbitService;
use UseTheFork\Synapse\Services\CrunchbaseService;

#[Description('Search Crunchbase for Company data.')]
final class CrunchbaseTool
{
    public function __construct(
      private readonly string $apiKey
    ) {
    }

    public function handle(
        #[Description('The crunchbase organizations `entityId`')]
        string $entityId,
    ): string {

        $crunchbaseService = new CrunchbaseService($this->apiKey);
        $results = $crunchbaseService->doOrganization($entityId);

        return $this->parseResults($results);
    }
  public function parseResults($result): string
  {

    $result = Arr::dot($result);
    return collect($result)->map(function ($value, $key) {
      return $key . ': ' . $value;
    })->implode("\n");
  }
}
