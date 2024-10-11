# Memory

It is often useful for an agent to have `memory`. For example, in a conversation, an agent should remember past inputs to respond appropriately.

Synapse provides several memory types that can be easily integrated into your agent.

## Getting Started

To get started, add the `HasMemory` interface and implement the `resolveMemory` method in your agent. You will also need to use the `ManagesMemory` trait. After that, select a memory type, have the `resolveMemory` method return it, and include the `@include('synapse::Parts.MemoryAsMessages')` snippet in your Blade prompt view.

> \[!IMPORTANT\]
> To keep things simple with memory, the `handle` method input should always have an `input` key. This is what is stored as the user's content. For example:\
> `$agent->handle(['input' => 'How Are you?']);`

In the example below, we use `CollectionMemory`, which stores memory for the duration of the application's lifecycle but clears it afterward.

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Contracts\Agent\HasMemory;
use UseTheFork\Synapse\Traits\Agent\ManagesMemory;

class SimpleAgent extends Agent implements HasMemory  // [!code focus]
{
    use ManagesMemory;  // [!code focus]

    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration();
    }

    public function resolveMemory(): Memory // [!code focus:4]
    {
        return new CollectionMemory();
    }
}
```

## Memory Methods

All memory types support the following methods:

- **clearMemory()**: Clears the agent's memory.
- **memory()**: Returns the current memory class.
- **getMemoryAsInputs()**: Converts the current memory into inputs for the agent's prompt.
- **addMessageToMemory(Message $message)**: Adds a message to the memory.
- **setMemory(array $messages)**: Sets the memory with a given array of messages.
- **defaultMemory()**: Registers the memory type.
