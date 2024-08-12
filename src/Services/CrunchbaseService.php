<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services;

use Illuminate\Support\Facades\Http;

class CrunchbaseService
{
    public function __construct(
        private readonly string $apiKey
    ) {}

    public function doOrganization(string $entityId)
    {

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->timeout(90)
            ->get("https://api.crunchbase.com/api/v4/entities/organizations/{$entityId}?card_ids=fields,headquarters_address,child_organizations,child_ownerships,founders,ipos,jobs,key_employee_changes,layoffs,parent_organization,press_references,participated_funding_rounds,participated_funds,participated_investments,press_references&user_key={$this->apiKey}")->json();

        return $response;
    }
}
