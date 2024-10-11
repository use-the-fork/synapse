<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\ValueObject\Message;

class ConversationSummaryMemory implements Memory
{
    protected Message $agentMemory;

    protected Integration $integration;

    /**
     * {@inheritdoc}
     */
    public function asInputs(): array
    {

        //MemoryAsMessages
        $payload = [
            'memory' => [],
            'memoryWithMessages' => [],
        ];

        $payload['memoryWithMessages'][] = "<message type='user'>\n## Summary of conversation earlier: \n{$this->agentMemory->content()}\n</message>";
        $payload['memory'][] = "Summary of conversation earlier: {$this->agentMemory->content()}";

        return [
            'memoryWithMessages' => implode("\n", $payload['memoryWithMessages']),
            'memory' => implode("\n", $payload['memory']),
        ];
    }

    public function boot(?PendingAgentTask $pendingAgentTask = null): void
    {
        $this->clear();
        $this->integration = $pendingAgentTask->agent()->integration();
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->agentMemory = Message::make([
            'role' => 'user',
            'content' => '',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function create(Message $message): void
    {
        $summaryPrompt = view('synapse::Prompts.ConversationSummaryPrompt', [
            'toDate' => $this->agentMemory->content(),
            'role' => $message->role(),
            'content' => json_encode($message->content()),
            'tool' => [
                'name' => $message->toolName(),
                'arguments' => $message->toolArguments(),
                'result' => $message->toolContent(),
            ],
        ])->render();

        $updatedMessage = Message::make([
            'role' => 'user',
            'content' => $summaryPrompt,
        ]);

        $result = $this->integration->handleCompletion($updatedMessage);

        $this->agentMemory = Message::make([
            'role' => 'user',
            'content' => $result->content(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return [$this->agentMemory];
    }

    /**
     * {@inheritdoc}
     */
    public function load(): void {}

    /**
     * {@inheritdoc}
     */
    public function set(array $messages): void
    {

        $promptTemplate = '';
        collect($messages)->each(function (Message $message) use (&$promptTemplate): void {
            $promptTemplate .= "\n{$message->role()}: {$message->content()}";
        });

        $summaryPrompt = view('synapse::Prompts.ConversationSummaryPrompt', [
            'toDate' => $this->agentMemory->content(),
            'role' => 'user',
            'content' => $promptTemplate,
        ])->render();

        $result = $this->integration->handleCompletion(Message::make([
            'role' => 'user',
            'content' => $summaryPrompt,
        ]));

        $this->agentMemory = Message::make([
            'role' => 'user',
            'content' => $result->content(),
        ]);
    }
}
