<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Firecrawl\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class FirecrawlRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $url  The URL to be used for extraction.
     */
    public function __construct(
        public readonly string $url
    ) {}

    /**
     * {@inheritdoc}
     */
    public function defaultBody(): array
    {
        return [
            'url' => $this->url,
            'formats' => ['markdown']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function resolveEndpoint(): string
    {
        return '/v1/scrape';
    }
}
