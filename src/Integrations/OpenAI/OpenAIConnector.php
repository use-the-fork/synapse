<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\OpenAI;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;

// implementation of https://github.com/bootstrapguru/dexor/blob/main/app/Integrations/OpenAI/OpenAIConnector.php
class OpenAIConnector extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors, HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    public function __construct()
    {
        //
    }

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.openai.com/v1';

    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {

        return [
            'Authorization' => 'Bearer '.config('synapse.integrations.openai.key'),
        ];
    }
}
