<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Exceptions\NotImplementedException;
use UseTheFork\Synapse\Integrations\Connectors\Claude\ClaudeAIConnector;
use UseTheFork\Synapse\ValueObject\EmbeddingResponse;
use UseTheFork\Synapse\ValueObject\Message;

class ClaudeIntegration implements Integration
{
    /**
     * {@inheritdoc}
     */
    public function handlePendingAgentTaskCompletion(
        PendingAgentTask $pendingAgentTask
    ): PendingAgentTask {

        $claudeAIConnector = new ClaudeAIConnector;
        $message = $claudeAIConnector->doCompletionRequest(
            prompt: $pendingAgentTask->currentIteration()->getPromptChain(),
            tools: $pendingAgentTask->tools(),
            extraAgentArgs: $pendingAgentTask->currentIteration()->getExtraAgentArgs()
        );

        $pendingAgentTask->currentIteration()->setResponse($message);

        return $pendingAgentTask;
    }

    /**
     * {@inheritdoc}
     */
    public function handleCompletion(
        Message $message,
        array $extraAgentArgs = []
    ): Message {
        $claudeAIConnector = new ClaudeAIConnector;

        return $claudeAIConnector->doCompletionRequest(
            prompt: [$message],
            extraAgentArgs: $extraAgentArgs
        );
    }

    public function createEmbeddings(string $input, array $extraAgentArgs = []): EmbeddingResponse
    {
        throw new NotImplementedException('Claude does not support embedding creation.');
    }
}
