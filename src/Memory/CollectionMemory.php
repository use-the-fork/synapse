<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Constants\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Memory\Contracts\Memory;

class CollectionMemory implements Memory
{
    protected Collection $agentMemory;

    public function __construct()
    {
        $this->agentMemory = collect();
    }

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
        $messages = $this->agentMemory->toArray();

        foreach ($messages as $message) {
            if ($message['role'] == Role::IMAGE_URL) {
                $payload['memoryWithMessages'][] = "<message type='".Role::IMAGE_URL."'>\n{$message['image']['url']}}\n</message>";
            } elseif ($message['role'] == Role::TOOL) {

                $tool = base64_encode(json_encode([
                    'name' => $message['tool_name'],
                    'id' => $message['tool_call_id'],
                    'arguments' => $message['tool_arguments'],
                ]));

                $payload['memoryWithMessages'][] = "<message type='".Role::ASSISTANT."' tool='{$tool}'>\n</message>";
                $payload['memoryWithMessages'][] = "<message type='".Role::TOOL."' tool='{$tool}'>\n{$message['content']}\n</message>";

                $payload['memory'][] = Role::ASSISTANT.": Call Tool `{$message['tool_name']}` with input `{$message['tool_arguments']}`";
                $payload['memory'][] = "{$message['tool_name']} response: {$message['content']}";

            } else {
                $payload['memoryWithMessages'][] = "<message type='{$message['role']}'>\n{$message['content']}\n</message>";
                $payload['memory'][] = "{$message['role']}: {$message['content']}";
            }
        }

        return [
            'memoryWithMessages' => implode("\n", $payload['memoryWithMessages']),
            'memory' => implode("\n", $payload['memory']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->agentMemory = collect();
    }

    /**
     * {@inheritdoc}
     */
    public function create(Message $message): void
    {
        $this->agentMemory->push($message->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return $this->agentMemory->toArray();
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
        $this->agentMemory = collect($messages);
    }
}
