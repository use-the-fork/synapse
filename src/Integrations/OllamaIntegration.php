<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Exceptions\NotImplementedException;
use UseTheFork\Synapse\Integrations\Connectors\Ollama\OllamaAIConnector;
use UseTheFork\Synapse\ValueObject\EmbeddingResponse;
use UseTheFork\Synapse\ValueObject\Message;

class OllamaIntegration implements Integration
{
    public function createEmbeddings(string $input, array $extraAgentArgs = []): EmbeddingResponse
    {
        throw new NotImplementedException('Claude does not support embedding creation.');
    }

    /**
     * {@inheritdoc}
     */
    public function handleCompletion(
        Message $message,
        array $extraAgentArgs = []
    ): Message {
        $ollamaAIConnector = new OllamaAIConnector;

        return $ollamaAIConnector->doCompletionRequest(
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

        $ollamaAIConnector = new OllamaAIConnector;
        $message = $ollamaAIConnector->doCompletionRequest(
            prompt: $pendingAgentTask->getPromptChain(),
            tools: $pendingAgentTask->tools(),
            extraAgentArgs: $pendingAgentTask->getExtraAgentArgs()
        );

        $pendingAgentTask->setResponse($message);

        return $pendingAgentTask;
    }
}
