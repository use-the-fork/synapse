# Agent Logs Traits

The `LogsAgentActivity` trait allows your agent to log activity at each step of its lifecycle. Similar to event dispatching, this trait logs key actions throughout the agent's execution.

To enable logging, simply add the `LogsAgentActivity` trait to your agent.

## Example

```php
<?php

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\LogsAgentActivity;

class SimpleAgent extends Agent
{
    use LogsAgentActivity; // [!code focus]

    public function resolvePromptView(): string
    {
        return 'synapse::Prompts.SimpleAgentPrompt';
    }

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration();
    }
}
```
