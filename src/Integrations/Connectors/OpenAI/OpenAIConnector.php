<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Connectors\OpenAI;

use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use UseTheFork\Synapse\Agents\PendingAgentTask;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\EmbeddingsRequest;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ValidateOutputRequest;
use UseTheFork\Synapse\Integrations\Contracts\Integration;
use UseTheFork\Synapse\Integrations\ValueObjects\EmbeddingResponse;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Integrations\ValueObjects\Response;
use UseTheFork\Synapse\Tools\Contracts\Tool;

// implementation of https://github.com/bootstrapguru/dexor/blob/main/app/Integrations/OpenAI/OpenAIConnector.php
class OpenAIConnector extends Connector implements Integration
{
    use AcceptsJson, AlwaysThrowOnErrors, HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;


    public function handleCompletion(
        PendingAgentTask $pendingAgentTask
    ): PendingAgentTask {

        $agentResponse = $this->send(new ChatRequest(
            prompt: $pendingAgentTask->currentIteration()->getPromptChain(),
            tools: $pendingAgentTask->tools(),
            extraAgentArgs: $pendingAgentTask->currentIteration()->getExtraAgentArgs()
                           ))->dto();

        $pendingAgentTask->currentIteration()->setResponse($agentResponse);

        return $pendingAgentTask;
    }

    /**
     * Forces a model to output its response in a specific format.
     *
     * @param Message $message The chat message that is used for validation.
     * @param  array  $extraAgentArgs  Extra arguments to be passed to the agent.
     * @return Message The response from the chat request.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function handleValidationCompletion(
        Message $message,
        array $extraAgentArgs = []
    ): Message {
        $response = $this->send(new ValidateOutputRequest($message, $extraAgentArgs))->dto();

        dd(
            $response
        );

        return Message::make();
    }

    public function createEmbeddings(string $input, array $extraAgentArgs = []): EmbeddingResponse
    {
        return $this->send(new EmbeddingsRequest($input, $extraAgentArgs))->dto();
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.openai.com/v1';

    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.config('synapse.integrations.openai.key'),
        ];
    }
}
