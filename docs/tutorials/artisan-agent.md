# Laravel Artisan Agent Tutorial

Interested in building an agent but don’t know where to start? This tutorial will help those who are just starting to use Synapse or are interested in AI in general.

The agent we’ll build in this tutorial is available in Synapse.

## Let's Start

There are many Artisan commands, and I often forget all the options that come with them. For instance, `php artisan make:model` has many options like creating a seeder, migration, resource, etc., at the same time.

This tutorial will guide you through creating a command that asks an AI agent to generate the correct Artisan command and, if needed, execute it. Neat, right?

## 1. The Command

First, we set up a console command to call our agent. We’ll extend Laravel’s `Illuminate\Console\Command` class:

```php
<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Console\Commands;

use Illuminate\Console\Command;

class SynapseArtisan extends Command
{
    /**
     * @inheritdoc
     */
    public $description = 'Ask Synapse about an Artisan command';

    /**
     * @inheritdoc
     */
    public $signature = 'synapse:ask';

    /**
     * Run the command
     */
    public function handle(): int
    {
        return self::SUCCESS;
    }
}
```

This basic command will run when you execute `php artisan synapse:ask`.

## 2. `handle` it.

Now we add the logic for our command. We'll use Laravel’s excellent [Prompts package](https://laravel.com/docs/11.x/prompts) to ask the user for input.

```php
public function handle(): int
{
    $command = text('What would you like Artisan to do?');
    return $this->executeAgent($command);
}
```

Now we have the task. Let's move on to creating the agent.

## 3. The Prompt

Prompts are instructions given to the AI model. With Laravel's Blade system, we can dynamically build prompts using variables. Here's the `SynapseArtisanPrompt.blade.php`:

```html
<message type="system">
# Instruction
You are a Laravel command assistant. Given a user question, you must generate an `artisan` command that completes the task.
You never explain and always follow the Output Schema below.

This command MUST be compatible with Laravel {{$version}}.

@include('synapse::Parts.OutputSchema')
</message>
@include('synapse::Parts.MemoryAsMessages')
@include('synapse::Parts.Input')
```

We provide a `system` message outlining the task and include some important Synapse parts:

- **`OutputSchema`**: Defines the structure the agent’s response must follow.
- **`MemoryAsMessages`**: Injects any previous interactions with the user.
- **`Input`**: Displays the user’s input task.

Here's an example of the rendered prompt:

```html
<message type="system">
  # Instruction
  You are a Laravel command assistant. Generate an `artisan` command that completes the task.
  You never explain and always follow the Output Schema.

  This command MUST be compatible with Laravel 11.27.2.
  ### You must respond using the following schema. Immediately return valid JSON formatted data:
  '''json
  {
  "command": "(required|string) the artisan command to run."
  }
  '''
</message>
<message type="user">
  create a model migration for Flights
</message>
```

Now, we'll use a "Few Shot" technique by adding examples to improve the agent’s accuracy:

```html
<message type="system">
# Instruction
You are a Laravel command assistant. Generate an `artisan` command that completes the task.
You never explain and always follow the Output Schema.

This command MUST be compatible with Laravel {{$version}}.

@include('synapse::Parts.OutputSchema')
</message>
<message type="user">
  create a model migration for Flights
</message>
<message type="assistant">
  '''json
  {
  "command": "make:model Flight -m"
  }
  '''
</message>
<message type="user">
  a command that sends emails
</message>
<message type="assistant">
  '''json
  {
  "command": "make:command SendEmails"
  }
  '''
</message>
<message type="user">
  a model for flights include the migration
</message>
<message type="assistant">
  '''json
  {
  "command": "make:model Flight --migration"
  }
  '''
</message>
<message type="user">
  a model for flights include the migration resource and request
</message>
<message type="assistant">
  '''json
  {
  "command": "make:model Flight --controller --resource --requests"
  }
  '''
</message>
<message type="user">
  flight model overview
</message>
<message type="assistant">
  '''json
  {
  "command": "model:show Flight"
  }
  '''
</message>
<message type="user">
  flight controller
</message>
<message type="assistant">
  '''json
  {
  "command": "make:controller FlightController"
  }
  '''
</message>
<message type="user">
  erase and reseed the database forcefully
</message>
<message type="assistant">
  '''json
  {
  "command": "migrate:fresh --seed --force"
  }
  '''
</message>
<message type="user">
  what routes are available
</message>
<message type="assistant">
  '''json
  {
  "command": "route:list"
  }
  '''
</message>
<message type="user">
  rollback migrations 5 times
</message>
<message type="assistant">
  '''json
  {
  "command": "migrate:rollback --step=5"
  }
  '''
</message>
<message type="user">
  start a q worker
</message>
<message type="assistant">
  '''json
  {
  "command": "queue:work"
  }
  '''
</message>
@include('synapse::Parts.MemoryAsMessages')
@include('synapse::Parts.Input')
```

## 4. The Agent

Next, we create the agent, which combines the prompt, memory, inputs, and integration.

```php
<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasMemory;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Traits\Agent\ManagesMemory;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class SynapseArtisanAgent extends Agent implements HasOutputSchema, HasMemory
{
    use ValidatesOutputSchema;
    use ManagesMemory;

    protected string $promptView = 'synapse::Prompts.SynapseArtisanPrompt';

    public function resolveMemory(): Memory
    {
        return new CollectionMemory;
    }

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'command',
                'rules' => 'required|string',
                'description' => 'The Artisan command to run.',
            ]),
        ];
    }
}
```

In summary:
- We extend the base `Agent` class.
- Implement `HasOutputSchema` to define the output structure.
- Implement `HasMemory` to remember conversations.
- Use the `ValidatesOutputSchema` and `ManagesMemory` traits.

## 5. Complete the Command

Finally, we integrate the agent into the `SynapseArtisan` command.

```php
private function executeAgent(string $task): int
{
    $synapseArtisanAgent = new SynapseArtisanAgent;

    while (true) {
        $result = spin(
            callback: fn() => $synapseArtisanAgent->handle(['input' => $task, 'version' => Application::VERSION]),
            message: 'Loading...'
        );
        $result = $result->content();
        $command = $result['command'];

        $choice = select(
            label: $command,
            options: [
                'yes' => '✅ Yes (Run command)',
                'edit' => '✏ Edit (Modify command)',
                'revise' => '🔁 Revise (Request new command)',
                'cancel' => '🛑 Cancel',
            ]
        );

        switch ($choice) {
            case 'yes':
                Artisan::call($command);
                return self::SUCCESS;
            case 'edit':
                $command = text('Edit command:', $command);
                Artisan::call($command);
                return self::SUCCESS;
            case 'revise':
                $task = text('Revise command:');
                break;
            case 'cancel':
                return self::FAILURE;
        }
    }
}
```

The agent’s memory allows it to retain the conversation context, making it easier to adjust commands based on user feedback.

## 6. Profit!
That’s it! You can find the complete code in the Synapse repository. Run `php artisan synapse:ask` to test it out and generate your Artisan commands!
