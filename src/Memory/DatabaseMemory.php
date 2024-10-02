<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use UseTheFork\Synapse\Constants\Role;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Models\AgentMemory;
use UseTheFork\Synapse\ValueObject\Message;

class DatabaseMemory implements Memory
{
    protected AgentMemory $agentMemory;

    public function __construct()
    {
        $this->agentMemory = new AgentMemory;
        $this->agentMemory->save();
    }

    /**
     * {@inheritdoc}
     */
    public function asInputs(): array
    {
        $payload = [
            'memory' => [],
            'memoryWithMessages' => [],
        ];
        $messages = $this->agentMemory->messages->toArray();

        foreach ($messages as $message) {
            if ($message['role'] == Role::IMAGE_URL) {
                // TODO: Fix this it should put the content in to the message and encode the image info.
                $payload['memoryWithMessages'][] = "<message type='".Role::IMAGE_URL."'>\n{$message['image']['url']}}\n</message>";
            } elseif ($message['role'] == Role::TOOL) {

                $tool = base64_encode(json_encode([
                    'name' => $message['tool_name'],
                    'id' => $message['tool_call_id'],
                    'arguments' => $message['tool_arguments'],
                    'content' => $message['tool_content'],
                ]));

                $payload['memoryWithMessages'][] = "<message type='".Role::TOOL."' tool='{$tool}'>\n{$message['content']}\n</message>";

                $payload['memory'][] = Role::ASSISTANT.": Call Tool `{$message['tool_name']}` with input `{$message['tool_arguments']}`";
                $payload['memory'][] = "{$message['tool_name']} response: {$message['tool_content']}";

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
        $this->agentMemory->delete();

        $this->agentMemory = new AgentMemory;
        $this->agentMemory->save();
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return $this->agentMemory->messages->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function load(): void
    {
        $this->agentMemory->load('messages');
    }

    /**
     * {@inheritdoc}
     */
    public function set(array $messages): void
    {
        //First we delete all the agents memory
        $this->agentMemory->messages()->delete();

        //Iterate over the messages and insert them in to memory
        foreach ($messages as $message) {
            $message = Message::make($message);
            $this->agentMemory->messages()->create($message->toArray());
        }

    }

    /**
     * {@inheritdoc}
     */
    public function create(Message $message): void
    {
        $this->agentMemory->messages()->create($message->toArray());
    }
}
