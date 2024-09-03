<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Clearbit\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ClearbitCompanyRequest extends Request
{

    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $domain,
    ) {}

    public function resolveEndpoint(): string
    {
      return '/v2/companies/find';
    }

  protected function defaultQuery(): array
  {
    return [
      'domain' => $this->domain
    ];
  }
}
