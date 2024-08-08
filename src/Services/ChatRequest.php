<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Services\Request;

use UseTheFork\Synapse\Data\MessageData;
use UseTheFork\Synapse\Data\ToolCallData;
use UseTheFork\Synapse\Models\Thread;

class ChatRequest
{
    public function __construct(
        public Thread $thread,
        public array $tools
    ) {}

    /**
     * Data to be sent in the body of the request
     */
    public function defaultBody(): array
    {
        $agent = $this->thread->agent;

        return [
            'model' => $agent->model,
            'messages' => $this->formatMessages($agent),
            'tools' => array_values($this->tools),
        ];
    }

    private function formatMessages($agent): array
    {
        return [
            [
                'role' => 'system',
                'content' => $agent->prompt,
            ],
            ...$this->thread->messages->toArray(),
        ];
    }

    public function createDtoFromResponse(Response $response): MessageData
    {
        $data = $response->json();
        $message = $data['choices'][0]['message'] ?? [];
        $tools = collect([]);
        if (isset($message['tool_calls'])) {
            foreach ($message['tool_calls'] as $toolCall) {
                $tools->push(ToolCallData::from($toolCall));
            }

            $message['tool_calls'] = $tools;
        }

        return MessageData::from($message ?? []);
    }
}
