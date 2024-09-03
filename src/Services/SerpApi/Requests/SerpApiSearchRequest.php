<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\SerpApi\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SerpApiSearchRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $searchQuery,
        public readonly int $numberOfResults = 20,
        public readonly string $engine = 'google',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/search';
    }

    public function defaultQuery(): array
    {
        return [
            'q' => $this->searchQuery,
            'num' => $this->numberOfResults,
            'engine' => $this->engine,
        ];
    }
}
