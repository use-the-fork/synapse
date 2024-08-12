<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use UseTheFork\Synapse\Integrations\ValueObjects\MessageValueObject;
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

    public function create(MessageValueObject $message): void
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

  public function asString(): string
  {
    $payload = [];
    $messages = $this->agentMemory->messages->toArray();

    foreach ($messages as $message) {
      if($message['role'] == 'tool'){
        $payload[] = "assistant: Call Tool `{$message['tool_name']}` with input `{$message['tool_arguments']}`";
        $payload[] = "{$message['tool_name']} response: {$message['content']}";
      } else {
        $payload[] = "{$message['role']}: {$message['content']}";
      }
    }

    return implode("\n", $payload);
  }
}
