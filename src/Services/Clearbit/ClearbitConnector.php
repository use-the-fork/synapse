<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Clearbit;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;

class ClearbitConnector extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors, HasTimeout;

    protected int $connectTimeout = 60;

    /**
     * Initializes a new instance of the ClearbitConnector class.
     *
     * @param string $apiKey The API key.
     * @param string $type The type of call this Connector can make either `company` or `person`.
     */
    public function __construct(
        public readonly string $apiKey,
        public readonly string $type
    ) {
        //
    }

    /**
     * @inheritdoc
     *
     */
    public function resolveBaseUrl(): string
    {
        return match ($this->type) {
            'company' => 'https://company.clearbit.com',
            'person' => 'https://person-stream.clearbit.com',
        };
    }

    /**
     * @inheritdoc
     *
     */
    protected function defaultHeaders(): array
    {

        return [
            'Authorization' => 'Bearer '.$this->apiKey,
        ];
    }
}
