<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use Illuminate\Support\Arr;
use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Crunchbase\CrunchbaseConnector;
use UseTheFork\Synapse\Services\Crunchbase\Requests\CrunchbaseRequest;

final class CrunchbaseTool extends BaseTool implements Tool
{
    private string $apiKey;

    public function boot(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        $this->apiKey = config('synapse.services.crunchbase.key');

        if (empty($this->apiKey)) {
            throw new MissingApiKeyException('API (CRUNCHBASE_API_KEY) key is required.');
        }

        return $pendingAgentTask;
    }

    /**
     * Search Crunchbase for Company data.
     *
     * @param  string  $entityId  The crunchbase organizations `entityId`
     */

    public function handle(
        string $entityId,
    ): string {

        $crunchbaseConnector = new CrunchbaseConnector($this->apiKey);
        $results = $crunchbaseConnector->send(new CrunchbaseRequest($entityId))->array();

        return $this->parseResults($results);
    }

    public function parseResults($result): string
    {

        $result = Arr::dot($result);

        return collect($result)->map(function (string $value, string $key): string {
            return $key.': '.$value;
        })->implode("\n");
    }
}
