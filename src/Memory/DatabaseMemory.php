<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\Models\AgentMemory;

class DatabaseMemory implements Memory
{
    protected AgentMemory $agentMemory;

    public function __construct()
    {
        $this->agentMemory = new AgentMemory();
        $this->agentMemory->save();
    }

    public function create(Message $message): void
    {
        $this->agentMemory->messages()->create($message->toArray());
    }

    public function get(): array
    {
        return $this->agentMemory->messages->toArray();
    }

    public function load(): void
    {
        $this->agentMemory->load('messages');
    }

    public function asInputs(): array
    {
        $payload = [
            'memory' => [],
            'memoryWithMessages' => [],
          ];
        $messages = $this->agentMemory->messages->toArray();

        foreach ($messages as $message) {
            if ($message['role'] == Role::IMAGE_URL) {
              $payload['memoryWithMessages'][] = "<message type='".Role::IMAGE_URL."'>\n{$message['image']['url']}}\n</message>";
            } else if ($message['role'] == Role::TOOL) {

                $tool = base64_encode(json_encode([
                    'name' => $message['tool_name'],
                    'id' => $message['tool_call_id'],
                    'arguments' => $message['tool_arguments'],
                ]));

              $payload['memoryWithMessages'][] = "<message type='".Role::ASSISTANT."' tool='{$tool}'>\n</message>";
              $payload['memoryWithMessages'][] = "<message type='".Role::TOOL."' tool='{$tool}'>\n{$message['content']}\n</message>";

              $payload['memory'][] = Role::ASSISTANT . ": Call Tool `{$message['tool_name']}` with input `{$message['tool_arguments']}`";
              $payload['memory'][] = "{$message['tool_name']} response: {$message['content']}";

            } else {
              $payload['memoryWithMessages'][] = "<message type='{$message['role']}'>\n{$message['content']}\n</message>";
              $payload['memory'][] = "{$message['role']}: {$message['content']}";
            }
        }

      return [
        'memoryWithMessages' => implode("\n", $payload['memoryWithMessages']),
        'memory' => implode("\n", $payload['memory'])
      ];
    }
}
