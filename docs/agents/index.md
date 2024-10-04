# Agents

Agents are base of all synapse library builds. This means that any Tool, Memory, OutputSchema, or Vector Store must be attached to an agent.

## Getting Started

You should establish a standard place to keep your Agents. For example in Laravel, a sensible place would be to place them inside the `App/Agents`.

Create a new class and extend the abstract `Agents` class.

You will then need to define a `$promptView`. This is the blade view that the agent uses as it's prompt template.

You will also need to define a `defaultIntegration` method. This is the API connection that the agent should use when being invoked. See the Integrations section for more detail.

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;

class SimpleAgent extends Agent
{
    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    public function defaultIntegration(): Integration
    {
        return new OpenAIIntegration();
    }
}
```
