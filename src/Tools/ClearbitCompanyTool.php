<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\ClearbitService;
use UseTheFork\Synapse\Tools\Contracts\Tool;

#[Description('Search Clearbit Company data.')]
final class ClearbitCompanyTool extends BaseTool implements Tool
{
    private string $apiKey;

    public function __construct(?string $apiKey = null)
    {

      if (! empty($apiKey)) {
        $this->apiKey = $apiKey;
      }

      parent::__construct();
    }

    protected function initializeTool(): void
    {
      if (empty($this->apiKey) && ! empty(config('synapse.services.clearbit.key'))) {
        $this->apiKey = config('synapse.services.clearbit.key');

        return;
      }
      throw new \Exception('API (CLEARBIT_API_KEY) key is required.');
    }

    public function handle(
        #[Description('the Top Level domain name to lookup for example `clearbit.com`')]
        string $domain,
    ): string {

        $clearbitService = new ClearbitService($this->apiKey);
        $results = $clearbitService->searchCompany($domain);

        return $this->parseResults($results);
    }
  public function parseResults($result): string
  {

    if (
      !empty($result['error'])
    ) {
      return "Error: {$result['error']['type']} - {$result['error']['message']}";
    }

    $snip = collect([]);
    if (!empty($result['name'])) {
      $snip->push("Company Name: {$result['name']}");
    }

    if (!empty($result['legalName'])) {
      $snip->push("Legal Name: {$result['legalName']}");
    }

    if (!empty($result['description'])) {
      $snip->push("Description: {$result['description']}");
    }

    if (!empty($result['category']['sector'])) {
      $snip->push("Category Sector: {$result['category']['sector']}");
    }

    if (!empty($result['category']['industry'])) {
      $snip->push("Category Industry: {$result['category']['industry']}");
    }

    if (!empty($result['tags'])) {
      $snip->push('Tags:' . implode(', ', $result['tags']));
    }

    return $snip->implode("\n");
  }
}
