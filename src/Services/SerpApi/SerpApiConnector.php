<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\SerpApi;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;

class SerpApiConnector extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors, HasTimeout;

    protected int $connectTimeout = 60;

    /**
     * Initializes a new instance of the SerpApiConnector class.
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
        return 'https://serpapi.com';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultQuery(): array
    {
        return [
            'api_key' => $this->apiKey,
        ];
    }
}
