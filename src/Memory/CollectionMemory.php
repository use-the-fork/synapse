<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Integrations\Enums\Role;
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

    public function asString(): string
    {
        $payload = [];
        $messages = $this->agentMemory->toArray();

        foreach ($messages as $message) {
            if ($message['role'] == 'tool') {

                $tool = base64_encode(json_encode([
                    'name' => $message['tool_name'],
                    'id' => $message['tool_call_id'],
                    'arguments' => $message['tool_arguments'],
                ]));

                $payload[] = "<message type='".Role::ASSISTANT."' tool='{$tool}'></message>";

                $payload[] = "<message type='".Role::TOOL."' tool='{$tool}'>
         {$message['content']}
        </message>";

            } else {
                $payload[] = "<message type='{$message['role']}'>
         {$message['content']}
        </message>";
            }
        }

        return implode("\n", $payload);
    }

    public function load(): void {}
}
