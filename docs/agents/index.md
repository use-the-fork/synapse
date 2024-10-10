# Agents

Agents are the core of the Synapse library. Every component, such as Tools, Memory, or OutputSchema, must be attached to an agent.

## Getting Started

It's recommended to define a standard location for your Agents. In Laravel, a good practice is to place them inside the `App/Agents` directory.

Create a new class that extends the abstract `Agent` class.

You will need to define a few methods and variables:

- **`$promptView`**: The Blade view that the agent uses as its prompt template. See the [Prompts section](/agents/prompts) for more details.
- **`resolveIntegration`**: The API connection the agent should use when invoked. See the [Integrations section](/agents/integrations) for more details.
```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;

class SimpleAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.SimplePrompt';
    
    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration;
    }
}
```
