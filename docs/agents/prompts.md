# Prompts

One of the powerful features of Synapse is its integration with Laravel's Blade system for generating prompts. This means you can leverage all Blade template functionalities within your prompts.

## How It Works
At runtime, Synapse retrieves the view specified in the agent's `$promptView` variable. It uses the `inputs` provided in the `handle` function, along with any inputs injected by traits such as memory. The prompt is rendered as plain text using Laravel's standard `view` method.

## Synapse Message Syntax
Synapse introduces custom tags to distinguish between message types in the compiled prompt view. This syntax allows you to easily create `few-shot` prompts by differentiating user, agent, system, and tool messages. The base tag used for this is `<message></message>`. Content wrapped in a `<message></message>` tag is treated as an individual message.

### `<message>` Anatomy
The `<message>` tag includes one required and two optional properties:

- **role**: Required. Must be one of `system`, `user`, or `assistant`.
- **tool**: Optional. A Base64-encoded JSON array containing keys like `tool_call_id`, `tool_name`, `tool_arguments`, and `tool_content`.
- **image**: Optional. A Base64-encoded JSON array containing keys like `image_url` and `detail`.

## Example

Here’s an example setup:

```php
<?php
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class WelcomeEmailAgent extends Agent implements HasOutputSchema
{
    use ValidatesOutputSchema;

    protected string $promptView = 'Prompts.WelcomeEmailPrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration;
    }

    public function resolveMemory(): Memory
    {
        return new CollectionMemory;
    }

    public function resolveOutputSchema(): array
    {
        return [
            SchemaRule::make([
                'name' => 'welcomeEmail',
                'rules' => 'required|string',
                'description' => 'the welcome email.',
            ]),
        ];
    }
}

$welcomeEmailAgent = new WelcomeEmailAgent;
$result = $welcomeEmailAgent->handle(['user' => User::first('2')]);
```

**WelcomeEmailPrompt.blade.php**:

```blade
<message role="user">
  # Instruction
  Write a HTML welcome email using the user's information as outlined below:

  ## User Information
  Name: {{$user->name}}
  Favorite Color: {{$user->favorite_color}}
  Favorite Emoji: {{$user->favorite_emoji}}

  @include('synapse::Parts.OutputSchema')
</message>
```

This would render as:

```text
<message type="user">
# Instruction
Write a HTML welcome email using the user's information as outlined below:

## User Information
Name: Greg
Favorite Color: Black
Favorite Emoji: 🤮

### You must respond using the following schema. Immediately return valid JSON formatted data:
'''json
{
    "answer": "(required|string) your final answer to the query."
}
'''
</message>
```

As shown, anything you can do in a Blade template, you can do in your prompts.
