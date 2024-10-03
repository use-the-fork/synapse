<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Connectors\Claude;

use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Exceptions\NotImplementedException;
use UseTheFork\Synapse\Integrations\Connectors\Claude\Requests\ChatRequest;
use UseTheFork\Synapse\ValueObject\EmbeddingResponse;
use UseTheFork\Synapse\ValueObject\Message;

// implementation of https://github.com/bootstrapguru/dexor/blob/main/app/Integrations/Claude/ClaudeAIConnector.php
class ClaudeAIConnector extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors, HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    /**
     * Handles the request to generate a chat response.
     *
     * @param  array<Message>  $prompt  The chat prompt.
     * @param  array<Tool>  $tools  Tools the agent has access to.
     * @param  array  $extraAgentArgs  Extra arguments to be passed to the agent.
     * @return Message The response from the chat request.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function doCompletionRequest(
        array $prompt,
        array $tools = [],
        array $extraAgentArgs = []
    ): Message {
        return $this->send(new ChatRequest($prompt, $tools, $extraAgentArgs))->dto();
    }

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.anthropic.com/v1';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'x-api-key' => config('synapse.integrations.claude.key'),
            'anthropic-version' => '2023-06-01',
        ];
    }

    public function createEmbeddings(string $input, array $extraAgentArgs = []): EmbeddingResponse
    {
        throw new NotImplementedException('Claude does not support embedding creation.');
    }
}
