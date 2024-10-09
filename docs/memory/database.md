# Database Memory

Database memory uses models to store itself in your database. This means that memory can be retrived or modified by other agents on the fly.

To get started, you need to publish the migrations that `DatabaseMemory` uses by running the install command and saying yes to publishing the migrations.

```bash
php artisan synapse:install
```

From here you will need to add the `HasMemory` trait and the `resolveMemory` method of your agent. Then add the `ManagesMemory` trait and include the `@include('synapse::Parts.MemoryAsMessages')` snippet in your prompts blade view.

From here you need to add `DatabaseMemory` memory type to the `resolveMemory` method.

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
        return new DatabaseMemory();
    }
}
```

`DatabaseMemory` takes a ID as it's input if you would like to load a specific memory in to your agent. If the ID is not found a new memory is created.

```php
    public function resolveMemory(): Memory
    {
        return new DatabaseMemory(123);
    }
```
