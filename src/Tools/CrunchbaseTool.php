<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\Attributes\Description;
use UseTheFork\Synapse\Services\Crunchbase\CrunchbaseConnector;
use UseTheFork\Synapse\Services\Crunchbase\Requests\CrunchbaseRequest;
use UseTheFork\Synapse\Tools\Contracts\Tool;
use UseTheFork\Synapse\Tools\Exceptions\MissingApiKeyException;

#[Description('Search Crunchbase for Company data.')]
final class CrunchbaseTool extends BaseTool implements Tool
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
        if (! empty($this->apiKey)) {
            return;
        }

        if (empty($this->apiKey) && ! empty(config('synapse.services.crunchbase.key'))) {
            $this->apiKey = config('synapse.services.crunchbase.key');

            return;
        }
        throw new MissingApiKeyException('API (CRUNCHBASE_API_KEY) key is required.');
    }

    public function handle(
        #[Description('The crunchbase organizations `entityId`')]
        string $entityId,
    ): string {

        $crunchbaseConnector = new CrunchbaseConnector($this->apiKey);
        $crunchbaseRequest = new CrunchbaseRequest($entityId);
        $results = $crunchbaseConnector->send($crunchbaseRequest)->array();

        return $this->parseResults($results);
    }

    public function parseResults($result): string
    {

        $result = Arr::dot($result);

        return collect($result)->map(function ($value, $key) {
            return $key.': '.$value;
        })->implode("\n");
    }
}
