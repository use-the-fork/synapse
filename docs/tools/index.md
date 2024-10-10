# Tools

Tools allow your agent to interact with your application or external APIs. Essentially, tools are invokable classes that the agent can call using the provided parameters and method names.

Tools are inherently part of all agents, but they are not invoked unless the `resolveTools` method returns an array of tools that the agent can use. Below is an example where the `SerperTool` is added to an agent, allowing it to perform Google searches.

```php
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Contracts\Integration;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Integrations\OpenAIIntegration;
use UseTheFork\Synapse\Tools\SerperTool;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class SerperAgent extends Agent implements HasOutputSchema
{
    use ValidatesOutputSchema;

    protected string $promptView = 'synapse::Prompts.SimplePrompt';

    public function resolveIntegration(): Integration
    {
        return new OpenAIIntegration;
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

    protected function resolveTools(): array
    {
        return [new SerperTool];
    }
}
```

Synapse comes with several packaged tools. To learn more about them, see the [Packaged Tools section](/tools/packaged-tools). If you'd like to build custom tools, refer to the [Anatomy of a Tool section](/tools/anatomy-of-a-tool).
