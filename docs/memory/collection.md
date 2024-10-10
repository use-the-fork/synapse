# Collection Memory

Collection memory is temporary and lasts only for the duration of the application's lifecycle, storing messages in an array. Once the application finishes, the memory is lost.

## Getting Started

To use collection memory, follow these steps:

1. Add the `HasMemory` trait and implement the `resolveMemory` method in your agent.
1. Include the `ManagesMemory` trait in your agent.
1. In your Blade prompt view, use the `@include('synapse::Parts.MemoryAsMessages')` snippet to display the memory as messages.
1. Set the memory type to `CollectionMemory` in the `resolveMemory` method.

Example:

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
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
        return new CollectionMemory();
    }
}
```
