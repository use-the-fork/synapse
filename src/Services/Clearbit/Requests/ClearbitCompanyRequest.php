<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Clearbit\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ClearbitCompanyRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * Constructs a new instance of the class.
     *
     * @param  string  $domain  The domain to look up.
     */
    public function __construct(
        public readonly string $domain,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function resolveEndpoint(): string
    {
        return '/v2/companies/find';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultQuery(): array
    {
        return [
            'domain' => $this->domain,
        ];
    }
}
