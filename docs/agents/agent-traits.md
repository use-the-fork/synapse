# Agent Traits

To make managing various agent activities easier Synapse provides `traits` that can be added to your agent. These hooks will modify the `PendingAgentTask` during the agent lifecycle. 

## Example
As an example if you would like to add output Validation to your agent first you add the `HasOutputSchema` interface this adds the `resolveOutputSchema` that you add your rules to. Then you add the `ValidatesOutputSchema` trait and your good to go! To learn more about the `ValidatesOutputSchema` see the [ValidatesOutputSchema Trait Section](/traits/validates-output-schema).

```php

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Memory;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Memory\CollectionMemory;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

  class ExampleAgent extends Agent implements HasOutputSchema
  {
      use ValidatesOutputSchema;

      protected string $promptView = 'synapse::Prompts.SimplePrompt';

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
                  'name' => 'answer',
                  'rules' => 'required|string',
                  'description' => 'your final answer to the query.',
              ]),
          ];
      }
  }
```

To see all traits that are shipped with synapse please see the [traits section](/traits).
