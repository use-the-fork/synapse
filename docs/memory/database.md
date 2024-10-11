# Database Memory

Database memory persists data by storing it in your database, allowing other agents to retrieve or modify the memory on the fly.

## Getting Started

To use database memory, follow these steps:

1. Publish the migrations used by `DatabaseMemory` by running the install command and choosing to publish the migrations.

```bash
php artisan synapse:install
```

2. Add the `HasMemory` trait and implement the `resolveMemory` method in your agent.
3. Include the `ManagesMemory` trait in your agent.
4. In your Blade prompt view, use the `@include('synapse::Parts.MemoryAsMessages')` snippet to display the memory as messages.
5. Set the memory type to `DatabaseMemory` in the `resolveMemory` method.

> [!IMPORTANT]
> To keep things simple with memory, the `handle` method input should always have an `input` key. This is what is stored as the user's content. For example:  
> `$agent->handle(['input' => 'How Are you?']);`

Example:

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\DatabaseMemory;
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

## Using a Specific Memory ID

`DatabaseMemory` accepts an ID as its input. If the specified ID is not found, a new memory entry is created.

Example:

```php
public function resolveMemory(): Memory
{
    return new DatabaseMemory(123);
}
```
