<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Clearbit\ClearbitConnector;
use UseTheFork\Synapse\Services\Clearbit\Requests\ClearbitCompanyRequest;

final class ClearbitCompanyTool extends BaseTool implements Tool
{
    private string $apiKey;

    public function boot(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        $this->apiKey = config('synapse.services.clearbit.key', '');

        if (empty($this->apiKey)) {
            throw new MissingApiKeyException('API (CLEARBIT_API_KEY) key is required.');
        }

        return $pendingAgentTask;
    }

    /**
     * Search Clearbit Company data.
     *
     * @param  string  $domain  the Top Level domain name to lookup for example `clearbit.com`
     */
    public function handle(
        string $domain,
    ): string {

        $clearbitConnector = new ClearbitConnector($this->apiKey, 'company');
        $results = $clearbitConnector->send(new ClearbitCompanyRequest($domain))->array();

        return $this->parseResults($results);
    }

    /**
     * Parses the results of a query and returns a formatted string.
     *
     * @param  array  $result  The result of a query.
     * @return string The formatted string containing the parsed results. If an error is present in the result, the error message is returned.
     */
    public function parseResults(array $result): string
    {

        if (
            ! empty($result['error'])
        ) {
            return "Error: {$result['error']['type']} - {$result['error']['message']}";
        }

        $snip = collect([]);
        if (! empty($result['name'])) {
            $snip->push("Company Name: {$result['name']}");
        }

        if (! empty($result['legalName'])) {
            $snip->push("Legal Name: {$result['legalName']}");
        }

        if (! empty($result['description'])) {
            $snip->push("Description: {$result['description']}");
        }

        if (! empty($result['category']['sector'])) {
            $snip->push("Category Sector: {$result['category']['sector']}");
        }

        if (! empty($result['category']['industry'])) {
            $snip->push("Category Industry: {$result['category']['industry']}");
        }

        if (! empty($result['tags'])) {
            $snip->push('Tags:'.implode(', ', $result['tags']));
        }

        return $snip->implode("\n");
    }
}
