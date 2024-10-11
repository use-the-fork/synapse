# Conversation Summary Memory

Conversation Summary memory generates a summary of the entire conversation, including responses from the user, agent, and tool calls. This is a direct implementation of [Langchain's conversation summary memory](https://langchain-ai.github.io/langgraph/how-tos/memory/add-summary-conversation-history/).

## Getting Started

To implement conversation summary memory, follow these steps:

1. Add the `HasMemory` trait and implement the `resolveMemory` method in your agent.
2. Include the `ManagesMemory` trait in your agent.
3. In your Blade prompt view, use the `@include('synapse::Parts.MemoryAsMessages')` snippet to display the memory as messages.
4. Set the memory type to `ConversationSummaryMemory` in the `resolveMemory` method.

> [!IMPORTANT]
> To keep things simple with memory, the `handle` method input should always have an `input` key. This is what is stored as the user's content. For example:  
> `$agent->handle(['input' => 'How Are you?']);`

Example:

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\ConversationSummaryMemory;
use UseTheFork\Synapse\Contracts\Agent\HasMemory;
use UseTheFork\Synapse\Traits\Agent\ManagesMemory;

class SimpleAgent extends Agent implements HasMemory  // [!code focus]
{
    use ManagesMemory;  // [!code focus]

    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration();
    }

    public function resolveMemory(): Memory // [!code focus:4]
    {
        return new ConversationSummaryMemory();
    }
}
```
