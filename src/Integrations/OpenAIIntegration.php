<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\OpenAIConnector;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\EmbeddingsRequest;
use UseTheFork\Synapse\ValueObject\EmbeddingResponse;
use UseTheFork\Synapse\ValueObject\Message;

class OpenAIIntegration implements Integration
{
    public function createEmbeddings(string $input, array $extraAgentArgs = []): EmbeddingResponse
    {
        return $this->send(new EmbeddingsRequest($input, $extraAgentArgs))->dto();
    }

    /**
     * {@inheritdoc}
     */
    public function handleCompletion(
        Message $message,
        array $extraAgentArgs = []
    ): Message {
        $openAIConnector = new OpenAIConnector;

        return $openAIConnector->doCompletionRequest(
            prompt: [$message],
            extraAgentArgs: $extraAgentArgs
        );
    }

    /**
     * {@inheritdoc}
     */
    public function handlePendingAgentTaskCompletion(
        PendingAgentTask $pendingAgentTask
    ): PendingAgentTask {

        $openAIConnector = new OpenAIConnector;
        $message = $openAIConnector->doCompletionRequest(
            prompt: $pendingAgentTask->getPromptChain(),
            tools: $pendingAgentTask->tools(),
            extraAgentArgs: $pendingAgentTask->getExtraAgentArgs()
        );

        $pendingAgentTask->setResponse($message);

        return $pendingAgentTask;
    }
}
