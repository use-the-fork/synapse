<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Crunchbase\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class CrunchbaseRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * Class constructor.
     *
     * @param  string  $entityId  The Crunchbase company ID to retrieve.
     */
    public function __construct(
        public readonly string $entityId,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function resolveEndpoint(): string
    {
        return "/api/v4/entities/organizations/{$this->entityId}";
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultQuery(): array
    {
        return [
            'card_ids' => 'fields,headquarters_address,child_organizations,child_ownerships,founders,ipos,jobs,key_employee_changes,layoffs,parent_organization,press_references,participated_funding_rounds,participated_funds,participated_investments,press_references',
        ];
    }
}
