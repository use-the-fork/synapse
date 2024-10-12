# 🧠 Laravel Synapse

![Build Status](https://github.com/use-the-fork/laravel-synapse/actions/workflows/tests.yml/badge.svg)

[Click here to read the documentation](https://use-the-fork.github.io/laravel-synapse/)

</div>

Laravel Synapse allows you to seamlessly integrate and manage AI agents in your Laravel applications. Inspired by Langchain and Laravel Saloon, this package simplifies AI agent creation and management, giving you the tools to run them at scale.

## Features

- Supports multiple AI integrations, including OpenAI and Claude.
- Easily extendable agent lifecycle with customizable hooks.
- Memory options for agents: temporary (CollectionMemory) or persistent (DatabaseMemory).
- Use Laravel's Blade system to create dynamic prompts.
- Build complex `few-shot` agent prompts with message tagging.
- Extend functionality by creating custom tools that can interact with agents. Tools can even make additional API calls or invoke other agents.
- Prebuilt agents available for quick and easy use with popular integrations like OpenAI.

## Installation

> **Requires [PHP 8.1+](https://php.net/releases/)**

1. Install via Composer:

   ```bash
   composer require use-the-fork/laravel-synapse
   ```

1. Run the install command:

   ```bash
   php artisan synapse:install
   ```

1. If you plan to use `DatabaseMemory`, ensure you publish the migrations by saying "yes" during installation.

## Configuration

1. Set up your `.env` file with the required API keys:

   ```dotenv
   OPENAI_API_KEY=
   ANTHROPIC_API_KEY=
   ```

1. If packages are not autoloaded, add the service provider:

   For **Laravel 10**:

   ```php
   //config/app.php
   'providers' => [
       ...
       UseTheFork\Synapse\SynapseServiceProvider::class,
       ...
   ];
   ```

   For **Laravel 11**:

   ```php
   //bootstrap/providers.php
   <?php
   return [
       App\Providers\AppServiceProvider::class,
       UseTheFork\Synapse\SynapseServiceProvider::class,
   ];
   ```

## Usage

### Defining an Agent

Agents are the core of Synapse. To create an agent, extend the `Agent` class and define key methods like `resolveIntegration`, `resolveMemory`, and `$promptView`.

Example:

```php
<?php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Contracts\Agent\HasMemory;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;

class SimpleAgent extends Agent implements HasMemory
{
    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration();
    }

    public function resolveMemory(): Memory
    {
        return new CollectionMemory();
    }
}
```

### Memory Options

Synapse offers two types of memory for agents:

- **CollectionMemory**: Temporary memory that exists only for the duration of the application's lifecycle.
- **DatabaseMemory**: Persistent memory stored in your database, allowing multiple agents to share or modify it.

To switch between these options, simply change the memory type in the `resolveMemory` method.

Example using `DatabaseMemory`:

```php
public function resolveMemory(): Memory
{
    return new DatabaseMemory(123);  // Use a specific memory ID
}
```

### Building Prompts

Use Laravel's Blade system to create agent prompts. Leverage the custom `<message>` tags to distinguish between user, system, and tool messages.

Example Blade prompt view:

```blade
<message role="user">
  # Instruction
  Write a welcome email for the following user:

  ## User Information
  Name: {{$user->name}}
  Favorite Color: {{$user->favorite_color}}
  Favorite Emoji: {{$user->favorite_emoji}}

  @include('synapse::Parts.OutputSchema')
</message>
```

## Integrations

Synapse supports the following integrations:

- **OpenAI**: Use the `OpenAIIntegration` class in your agents.
- **Claude**: Use the `ClaudeIntegration` class in your agents.

To configure these integrations, add the appropriate API key to your `.env` file and specify the integration in the `resolveIntegration` method.

Example:

```php
public function resolveIntegration(): Integration
{
    return new OpenAIIntegration();
}
```

## Agent Lifecycle & Hooks

The Synapse agent lifecycle offers several points where you can hook in to modify the agent's behavior. These hooks allow for customizations such as adjusting input, memory, tools, and integration responses.

Refer to the full documentation for more details on available hooks and how to use them.

## Documentation

[Click here to read the documentation](https://use-the-fork.github.io/laravel-synapse/)

## Support Synapse's Development

If you would like to support my work, you can donate to my Ko-Fi page by simply buying me a coffee or two!

<a href='https://ko-fi.com/usethefork' target='_blank'><img height='35' style='border:0px;height:46px;' src='https://az743702.vo.msecnd.net/cdn/kofi3.png?v=0' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>

Thank you for using Laravel Synapse ❤️

## Credits

This project would not have been possible without:

- Saloon - https://github.com/saloonphp/saloon
- LangChain - https://python.langchain.com/
- Dexor - https://github.com/bootstrapguru/dexor/

## License

This package is open-source and licensed under the [MIT License](LICENSE.md).

---

Start building AI-driven applications with Laravel Synapse today!
