# Laravel-Synapse
## AI agents for all!
Laravel Synapse gives you the ability to create AI agents. Inspired by Langchain and Laravel Saloon this package aims to simplify integrating AI agents in to your laravel application and allowing you to run them at scale.

## Installation
> **Requires [PHP 8.1+](https://php.net/releases/)**

First, via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require use-the-fork/laravel-synapse
```

Next, execute the install command:

```bash
php artisan synapse:install
```

If you are not planning to use `DatabaseMemory` you do not need to publish the migrations.


## Get Started
### 1. Set up your `.env` with the following settings You can omit any that you are not planning to use:
```dotenv
OPENAI_API_KEY=
OPENAI_API_CHAT_MODEL=gpt-4-turbo
OPENAI_API_EMBEDDING_MODEL=text-embedding-ada-002

ANTHROPIC_API_KEY=
ANTHROPIC_API_CHAT_MODEL=claude-3-5-sonnet-20240620

SERPAPI_API_KEY=
SERPER_API_KEY=
CLEARBIT_API_KEY=
CRUNCHBASE_API_KEY=
FIRECRAWL_API_KEY=

```

### 2. If packages are not autoloaded, add the service provider:
For **Laravel 10**:

```php
//config/app.php
'providers' => [
    ...
    ...
    UseTheFork\Synapse\SynapseServiceProvider::class, // [!code highlight]
    ...
```
For **Laravel 11**:
```php
//bootstrap/providers.php
<?php
return [
    App\Providers\AppServiceProvider::class,
    UseTheFork\Synapse\SynapseServiceProvider::class, // [!code highlight]
];
```
Now, you're all set to use Synapse with Laravel.
