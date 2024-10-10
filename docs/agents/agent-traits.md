# Agent Traits

Synapse provides a set of `traits` to simplify managing various agent activities. These traits allow you to modify the `PendingAgentTask` during the agent lifecycle.

## Example
For instance, to add output validation to your agent, you would first implement the `HasOutputSchema` interface, which introduces the `resolveOutputSchema` method for defining your validation rules. Then, use the `ValidatesOutputSchema` trait, and you're all set! To learn more about the `ValidatesOutputSchema` trait, see the [ValidatesOutputSchema Trait Section](/traits/validates-output-schema).

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
                'description' => 'Your final answer to the query.',
            ]),
        ];
    }
}
```

To explore all traits that come with Synapse, visit the [traits section](/traits/index).
