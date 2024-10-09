# Memory

It is often desirable for an agent to have `memory` for example during a conversation it is ideal for an Agent to remember past inputs so it can respond properly.

Synapse has a few different memory types you can use with your agent and makes implementing them a snap.

## Getting Started

To get started, you will need to add the `HasMemory` trait and the `resolveMemory` method of your agent. Then add the `ManagesMemory` trait. From here you need to pick a memory type to use and have the `resolveMemory` method return it, and you need to include the `@include('synapse::Parts.MemoryAsMessages')` snippet in your prompts blade view. 

In the example below we use `CollectionMemory` which means the memory will be stored for the duration of the applications lifecycle but erased after that.

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

All memory types use the following methods:

- clearMemory() -> Clears the agents memory.
- memory() -> Returns the current memory class.
- getMemoryAsInputs() -> Returns the current memory converted in to inputs for the agents Prompt.
- addMessageToMemory(Message $message) -> Adds a message to the memory.
- setMemory(array $messages) -> Sets the memory with the given array of messages.
- defaultMemory() -> Registers the memory type.
