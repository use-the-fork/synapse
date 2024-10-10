# Hook Traits

To modify a part of a `PendingAgentTask`, you can do so directly within the agent class.

To begin, add the interface that corresponds to the part of the agent lifecycle you want to hook into. This will add the appropriate resolver methods to your agent. Below is a list of available hooks. For more details on when these hooks fire, refer to the [Agent Lifecycle section](/agents/agent-lifecycle).

- `HasBootAgentHook`
- `HasStartThreadHook`
- `HasStartIterationHook`
- `HasPromptGeneratedHook`
- `HasPromptParsedHook`
- `HasIntegrationResponseHook`
- `HasStartToolCallHook`
- `HasEndToolCallHook`
- `HasAgentFinishHook`
- `HasEndIterationHook`
- `HasEndThreadHook`

Next, add the `ManagesHooks` trait, and you’re all set! This allows you to modify the `PendingAgentTask` or `generatedPrompt` during the agent's lifecycle.

## Example

In the example below, we use the `ManagesHooks` trait to implement the `hookPromptGenerated` method via the `HasPromptGeneratedHook` interface. This allows us to modify the prompt after it's generated and before it's broken into messages.

```php
<?php

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\ManagesHooks;
use UseTheFork\Synapse\Contracts\Agent\HasHooks;
use UseTheFork\Synapse\Contracts\Agent\Hooks\HasPromptGeneratedHook;

class SimpleAgent extends Agent implements HasPromptGeneratedHook // [!code focus]
{
    use ManagesHooks; // [!code focus]

    public function resolvePromptView(): string
    {
        return 'synapse::Prompts.SimpleAgentPrompt';
    }

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration();
    }

    public function hookPromptGenerated(string $generatedPrompt): string // [!code focus:4]
    {
        return str($generatedPrompt)->replace('hi', 'HELLO!')->toString();
    }
}
```
