# Hook Traits

To get started you will need to add the interface that matches the part of the agent lifecycle you want to hook in to. This will add the proper resolver methods to your agent. Below is a listing of hooks that are available for more information on when these hooks fire please see the [Agent Lifecycle section](/agent-lifecycle).

- `HasBootAgentHook`
- `HasStartThreadHook`
- `HasStartIterationHook`
- `HasStartIterationHook`
- `HasPromptGeneratedHook`
- `HasPromptParsedHook`
- `HasIntegrationResponseHook`
- `HasStartToolCallHook`
- `HasEndToolCallHook`
- `HasAgentFinishHook`
- `HasEndIterationHook`
- `HasEndThreadHook`

```php
<?php

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Agents\Integrations\OpenAI\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\ManagesHooks;
use UseTheFork\Synapse\Contracts\Agent\HasHooks;

class SimpleAgent extends Agent implements HasMemory, HasPromptGeneratedHook  // [!code focus]
{

    use ManagesHooks; // [!code focus]

    public function resolvePromptView(): string
    {
        return 'synapse::Prompts.SimpleAgentPrompt';
    }

    public function resolveIntegration(): string
    {
        return new OpenAIIntegration();
    }

    public function hookPromptGenerated(string $generatedPrompt): string; // [!code focus:4]
    {
        return str($generatedPrompt)->replace('hi', 'HELLO!')->toString();
    }

}
```

In the above example we tell our `ManagesHooks` trait to execute the `hookPromptGenerated` via `HasPromptGeneratedHook` integration. As a result we can modify the prompt after it's been generated and before it's been broken in to messages.
