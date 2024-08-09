<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\Models\AgentMemory;
use UseTheFork\Synapse\ValueObject\MessageValueObject;

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
}
