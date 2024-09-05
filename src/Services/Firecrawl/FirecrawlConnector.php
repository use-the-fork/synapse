<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Firecrawl;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;

class FirecrawlConnector extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors, HasTimeout;

    protected int $connectTimeout = 60;

    /**
     * Initializes a new instance of the FirecrawlConnector class.
     *
     * @param  string  $apiKey  The API key.
     */
    public function __construct(
        public readonly string $apiKey
    ) {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.firecrawl.dev';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->apiKey,
        ];
    }
}
