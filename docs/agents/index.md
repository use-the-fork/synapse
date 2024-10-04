# Agents

Agents are base of all synapse library builds. This means that any Tool, Memory, OutputSchema, etc. must be attached to an agent.

## Getting Started

You should establish a standard place to keep your Agents. For example in Laravel, a sensible place would be to place them inside the `App/Agents`.

Create a new class and extend the abstract `Agents` class.

You will then need to define a few methods and variables to get started:

* `$promptView` - This is the blade view that the agent uses as it's prompt template. See the [Prompts section](/agents/prompts) for more detail.
* `resolveIntegration` - This is the API connection that the agent should use when being invoked. See the [Integrations section](/agents/integrations) for more detail.
* `resolveMemory` - This is the memory that the agent will use when both talking to the user and making tool calls. See the [Memory section](/agents/memory) for more detail.

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Memory\CollectionMemory;

class SimpleAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.SimplePrompt';
    
    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration;
    }
    
    public function resolveMemory(): Memory
    {
        return new CollectionMemory;
    }
}
```
