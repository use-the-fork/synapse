<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Memory\Contracts\Memory;

class CollectionMemory implements Memory
{
    protected Collection $agentMemory;

    public function __construct()
    {
        $this->agentMemory = collect();
    }

    public function create(Message $message): void
    {
        $this->agentMemory->push($message->toArray());
    }

    public function get(): array
    {
        return $this->agentMemory->toArray();
    }

    public function load(): void {}
}
