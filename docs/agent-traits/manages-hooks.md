# ManagesHooks Trait

To get started you will need to add the `HasHooks` interface. This will add the proper resolver methods to your agent.

```php
<?php

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Agents\Integrations\OpenAI\OpenAIIntegration;
use UseTheFork\Synapse\Traits\Agent\ManagesHooks;
use UseTheFork\Synapse\Contracts\Agent\HasHooks;

class SimpleAgent extends Agent implements HasMemory, HasHooks  // [!code focus]
{

    use ManagesHooks;

    public function resolvePromptView(): string
    {
        return 'synapse::Prompts.SimpleAgentPrompt';
    }

    public function resolveIntegration(): string
    {
        return new OpenAIIntegration();
    }

    public function hookAgentFinish(PendingAgentTask $pendingAgentTask): PendingAgentTask; // [!code focus:17]

    public function hookEndIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookEndThread(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookEndToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookIntegrationResponse(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookStartIteration(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookStartThread(PendingAgentTask $pendingAgentTask): PendingAgentTask;

    public function hookStartToolCall(PendingAgentTask $pendingAgentTask): PendingAgentTask;

}
```

You are not free to modify the `PendingAgentTask` as you see fit or fire other events within the agent lifecycle.
