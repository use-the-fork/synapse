# Agents

Agents are the core of the Synapse library. Every component, such as Tools, Memory, or OutputSchema, must be attached to an agent.

## Getting Started

It's recommended to define a standard location for your Agents. In Laravel, a good practice is to place them inside the `App/Agents` directory.

Create a new class that extends the abstract `Agent` class.

You will need to define a few methods, interfaces, and variables:

- **`$promptView`**: The Blade view that the agent uses as its prompt template. See the [Prompts section](/agents/prompts) for more details.
- **`HasIntegration`**: This interface tells the agent to look for the `resolveIntegration` method when booting. See the [Integrations section](/agents/integrations) for more details.
- **`resolveIntegration`**: The API connection the agent should use when invoked. See the [Integrations section](/agents/integrations) for more details.

> [!TIP]
> If you set a default integration in your config file, you do not need to add the `resolveIntegration` method or the `HasIntegration` interface to your agent.

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Contracts\Agent\HasIntegration;

class SimpleAgent extends Agent implements HasIntegration
{
    protected string $promptView = 'synapse::Prompts.SimplePrompt';
    
    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration;
    }
}
```
