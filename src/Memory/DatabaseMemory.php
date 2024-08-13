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
              $payload['memoryWithMessages'][] = "<message type='".Role::IMAGE_URL."' image='{$message['image']['url']}'></message>";
            } else if ($message['role'] == Role::TOOL) {
                $tool = base64_encode(json_encode([
                    'name' => $message['tool']['name'],
                    'id' => $message['tool']['call_id'],
                    'arguments' => $message['tool']['arguments'],
                ]));

              $payload['memoryWithMessages'][] = "<message type='".Role::ASSISTANT."' tool='{$tool}'></message>";
              $payload['memoryWithMessages'][] = "<message type='".Role::TOOL."' tool='{$tool}'>{$message['content']}</message>";

              $payload['memory'][] = Role::ASSISTANT . ": Call Tool `{$message['tool_name']}` with input `{$message['tool_arguments']}`";
              $payload['memory'][] = "{$message['tool_name']} response: {$message['content']}";

            } else {
              $payload['memoryWithMessages'][] = "<message type='{$message['role']}'>{$message['content']}</message>";
              $payload['memory'][] = "{$message['role']}: {$message['content']}";
            }
        }

      return [
        'memoryWithMessages' => implode("\n", $payload),
        'memory' => implode("\n", $payload)
      ];
    }
}
