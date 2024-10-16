# Laravel Artisan Agent Tutorial

So you want to build an agent but don't know where to start? This tutorial is meant to help those who are just starting to use Synapse or are interested in AI in general.

The Agent that we will be building in this tutorial is available as part of Synapse so feel free to review the code as I will link each file .

## Lets start

As you may know there are A LOT of artisan commands and many of them I can't remember but I know would be useful in my development process.

For example I often make models using `php artisan make:model` however the `make:model` command has plenty of options connected to it to let you also create a seeder, migration, resource, test, etc at the same time.

I do not remember all these options and the fact is that's for only one command there are tons of others with simalr options.

So here is the idea create a command that will ask an agent to generate an artisan command and if needed execute it. Neat hu?

Okay so lets get going.

## 1. The Command

We will need to start by setting up a console command to call our agent. To do this we will extend the laravel `Illuminate\Console\Command` class.

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
    public $signature = 'synapse:ask {task?}';

    /**
     * Run the command
     */
    public function handle(): int
    {

        return self::SUCCESS;
    }
}
```

In the above we set up our base command that will run when we execute `php artisan synapse:ask`

Since we are being good developers lets set up a unit test here.

## 2. `handle` it.

Now we can lay the foundation for what our command is going to do. Fot this we are going to use the excellent prompts package (https://laravel.com/docs/11.x/prompts).

We need our command to ask the user for input if the `task` parameter wasn't filled in.

```php
    public function handle(): int
    {

        if($this->argument('task')){
            $this->executeAgent($this->argument('task'));
        }

        $command = text('What would you like artisan to do?');
        $this->executeAgent($command);

        return self::SUCCESS;
    }
```

Okay easy enough so far. Now that we have our task the next thing we want to do is create our Agent. We will start with the "prompt" and then move on to the class.

## 3. The Prompt

Prompts may seem intimidating but in reality they are pretty simple to understand. All a prompt is is a set of text based instructions that you would like an AI modal to execute. In reality the AI model is just predicting what the next "word" (token) should be when it's responding to you.

This is the reason that Laravel blade templates are such a good fit for prompt building. Because we can dynamically inject variables or make desions based on varibles we can easily build a "dynamic" prompt for most of our use cases.

Below is the `SynapseArtisanPrompt.blade.php` I have created for our agent.

```bladehtml
<message type="system">
# Instruction
You are a laravel command assistant. Given a user question you must generate a `artisan` command that would complete the task.
You never explain and always follow the below Output Schema.

This command MUST be compatible with is Laravel {{$version}}

@include('synapse::Parts.OutputSchema')
</message>
@include('synapse::Parts.MemoryAsMessages')
@include('synapse::Parts.Input')
```

So in the above we have set up our blade template. We start it with a `system` message that tells the agent what task it will be completing for the user. We left a spot to add our Laravel version that will be inserted as part of the prompt inputs.

We also included some Synapse parts:

- `@include('synapse::Parts.OutputSchema')` - This inserts the output schema the agent must follow.
- `@include('synapse::Parts.MemoryAsMessages')` - This inserts the agents "Memory" IE any previous interactions we had with the user. This will be useful if we want the agent to modify the command.
- `@include('synapse::Parts.Input')` - This inserts the `task` our command first asked for.

Rendered our view would look like this:

```html
<message type="system">
  # Instruction You are a laravel command assistant. Given a user question you
  must generate a `artisan` command that would complete the task. You never
  explain and always follow the below Output Schema. This command MUST be
  compatible with is Laravel {{$version}} ### You must respond using the
  following schema. Immediately return valid JSON formatted data: '''json {
  "command": "required" } '''
</message>
<message type="user">
  create a Flights Model with a resource, policy, and controller.
</message>
```

??????? TODO Validate this.

This is good but Agents typically do better when they have examples to work from. This is called "Few Shot" prompting. To Do this we are going to "fake" some memory directly in the prompt as if we have already asked for commands and the agent responded accordingly.

??????? TODO ADD FEW SHOT

## 3. The Agent

Okay! Now that our prompt is done it's time to create our agent. An agent is the glue that combines the Prompt, Memory, Input variables, and integration. It's where the magic happens!

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
                                 'description' => 'the artisan command to run.',
                             ]),
        ];
    }
}
```

So starting from the top down. We have done the following:

- Extended the base `Agent` class.
- Implment `HasOutputSchema` so that our agent knows we have a `resolveOutputSchema` function. Becuase we want our agent to respond in a very specific way this is necessary.
- We also implement `HasMemory` since our agent needs to remember the conversation we are having.
- Since we are using an `OutputSchema` we also have to add the `ValidatesOutputSchema` trait.
- And since we are using `HasMemory` we also need the `ManagesMemory` trait.
- We point our `$promptView` to the view we created in the previous step.
- We define a `resolveMemory` method. Here I have chosen to use `CollectionMemory` becuase I don't want to save the conversation I have had with the agent after the command has run. You can read more about [memory here](/memory/).
- Finally, we define a `resolveOutputSchema` method. This tells the agent the exact output we reqire as well as validates the agents' response. You can read more about [OutputSchema here](/traits/validates-output-schema.md).

## 4. Finish up the command.

That's it! Now we need to pull our agent in to our command.
