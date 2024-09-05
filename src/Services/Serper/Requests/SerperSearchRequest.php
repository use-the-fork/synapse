<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Serper\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SerperSearchRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * Constructor for the SerperSearch Request.
     *
     * @param  string  $searchQuery  The search query.
     * @param  string  $searchType  The search type.
     * @param  int  $num  The number of search results to return (optional, defaults to 20).
     */
    public function __construct(
        public readonly string $searchQuery,
        public readonly string $searchType,
        public readonly int $num = 20,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function resolveEndpoint(): string
    {
        return match ($this->searchType) {
            'search' => '/search',
            'places' => '/places',
            'news' => '/news',
        };
    }

    /**
     * {@inheritdoc}
     */
    public function defaultBody(): array
    {
        return [
            'q' => $this->searchQuery,
            'num' => $this->num,
        ];
    }
}
