# Memory

Because of the nature of tool calls and user agent conversations in general All Synapse agents must have a memory type set to function.
When the `resolveMemory` method is not overwritten by your agent you will simply receive a Exception until one is added and a memory type is resolved.

## Getting Started

To get started, you will need to override the `resolveMemory` method of your agent with one of the Memory types available by Synapse

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\DatabaseMemory;

class SimpleAgent extends Agent
{

    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration();
    }

    public function resolveMemory(): Memory
    {
        return new CollectionMemory();
    }
}
```

All memory types use the following methods:

- clearMemory() -> Clears the agents memory.
- memory() -> Returns the current memory class.
- getMemoryAsInputs() -> Returns the current memory converted in to inputs for the agents Prompt.
- addMessageToMemory(Message $message) -> Adds a message to the memory.
- setMemory(array $messages) -> Sets the memory with the given array of messages.
- defaultMemory() -> Registers the memory type.

Next, you will need to add a trait to provide an implementation for the missing memory methods. Synapse has a trait for
some common memory implementations.

Continue reading below to understand more about the specific body type that you need.
