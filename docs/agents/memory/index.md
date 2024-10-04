# Memory

When working with agents it's often desirable for an agent to have "memory" or a record of the conversation so far.
Synapse makes this easy for you with built-in traits. Keep in mind that an agent has a temporary memory stored on the
`PendingAgentTask` class. This memory is used while the agent is running tool calls. For longer term memory ie memory
that is saved across multiple invokes you should use the below.

## Getting Started

To get started, you will need to add the `HasMemory` interface to your agent. This interface is required as it
bootstraps the agent with the required methods for memory.

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
