<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts;

use UseTheFork\Synapse\Agent\PendingAgentTask;
use UseTheFork\Synapse\ValueObject\EmbeddingResponse;
use UseTheFork\Synapse\ValueObject\Message;

interface Integration
{
    /**
     * Handles the request to generate a chat response.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The chat prompt.
     */
    public function handleCompletion(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    /**
     * Forces a model to output its response in a specific format.
     *
     * @param  Message  $message  The chat message that is used for validation.
     * @param  array  $extraAgentArgs  Extra arguments to be passed to the agent.
     * @return Message The response from the chat request.
     */
    public function handleValidationCompletion(Message $message, array $extraAgentArgs = []): Message;

    /**
     * Creates an embedding vector representing the input text.
     *
     * @param  string  $input  Input text to embed, encoded as a string or array of tokens.
     * @param  array  $extraAgentArgs  Extra arguments to be passed to the agent.
     * @return EmbeddingResponse The response from the request.
     */
    public function createEmbeddings(string $input, array $extraAgentArgs = []): EmbeddingResponse;
}
