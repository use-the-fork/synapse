# Collection Memory

Collection memory lasts only for the duration of the applications lifecycle and stores messages in an array. As a result the memory is lost when the applications finishes.

To get started, you will need to add the `HasMemory` trait and the `resolveMemory` method of your agent. Then add the `ManagesMemory` trait and you need to include the `@include('synapse::Parts.MemoryAsMessages')` snippet in your prompts blade view.

From here you need to add `CollectionMemory` memory type to the `resolveMemory` method.

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
