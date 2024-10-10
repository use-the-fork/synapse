# Agent Events Traits

You can enable your agent to fire events at each step of its lifecycle. For more details on the lifecycle, refer to the [Agent Lifecycle section](/agents/agent-lifecycle). The following events are dispatched:

- `UseTheFork\Synapse\Events\Agent\BootAgent::class;`
- `UseTheFork\Synapse\Events\Agent\StartThread::class;`
- `UseTheFork\Synapse\Events\Agent\PromptGenerated::class;`
- `UseTheFork\Synapse\Events\Agent\PromptParsed::class;`
- `UseTheFork\Synapse\Events\Agent\IntegrationResponse::class;`
- `UseTheFork\Synapse\Events\Agent\StartToolCall::class;`
- `UseTheFork\Synapse\Events\Agent\EndToolCall::class;`
- `UseTheFork\Synapse\Events\Agent\StartIteration::class;`
- `UseTheFork\Synapse\Events\Agent\EndIteration::class;`
- `UseTheFork\Synapse\Events\Agent\AgentFinish::class;`
- `UseTheFork\Synapse\Events\Agent\EndThread::class;`

All events receive the `PendingAgentTask` as input, except for `PromptGenerated`, which returns the prompt as a string, and `PromptParsed`, which returns an array of parsed messages.

To set up event handling, simply add the `HandlesAgentEvents` trait to your agent.

## Example

```php
<?php

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\HandlesAgentEvents;

class SimpleAgent extends Agent
{
    use HandlesAgentEvents; // [!code focus]

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
