# Prompts

One of the best parts of Synapse is it leverages Laravels blade system to put together it's prompts. This means anything you can do in a blade template you can also do in a prompt.

## How it works
At run time Synapse will first get the view you specified with the agent in the `$promptView` variable. It will then use the `inputs` you specified in the handle function as well as any injected inputs various traits such as memory adds. This is all run thru Laravel standard `view` method your prompt is rendered as plain text.

## Synapse Message Syntax
Synapse uses a few custom tags to resolve message types in the compiled prompt view. You can use this syntax inside your prompt views to distinguish user, agent, system, and tool calls. This lets you create `few-shot` prompts easily. The base tag for this is the `<message></message>` tag. All content wrapped in a `<message></message>` tag will be seen as individual message.

### `<message>` Anatomy
The `<message>` tag has one required prop and two optional props:
* **role**: Must be one of `system`, `user`, or `assistant`.
* **tool**: A Base64 Encoded JSON array that contains the following keys and values: `tool_call_id`, `tool_name`, `tool_arguments`, `tool_content`.
* **image**: A Base64 Encoded JSON array that contains the following keys and values: `image_url`, `detail`

## Example

Take the below example:

```php
<?php
  use UseTheFork\Synapse\Agent;
  use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
  use UseTheFork\Synapse\Contracts\Integration;
  use UseTheFork\Synapse\Contracts\Memory;
  use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
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

  $welcomeEmailAgent = new WelcomeEmailAgent
  $result = $welcomeEmailAgent->handle(['user' => User::first('2')]);

```

WelcomeEmailPrompt.blade.php
```bladehtml
<message role="user">
  # Instruction
  Write a HTML welcome email using the users information as outlined below:

  ## User Information
  Name: {{$user->name}}
  Favorite Color: {{$user->favorite_color}}
  Favorite Emoji: {{$user->favorite_emoji}}
  
  @include('synapse::Parts.OutputSchema')
</message>
```

The above example would then translate to
```text
<message type="user">
# Instruction
Write a HTML welcome email using the users information as outlined below:

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

As you can see anything you do in a blade template you cna do in your prompts. 
