# Tools
Tools are used to have your agent interface with your application or other APIs. Boiled down tools are invokable classes that the agent can call by using the passed in parameters and method name. 

Tools are built in to all agents by design but are never called unless the `resolveTools` method returns an array of tools the agent can use. You can see an example in the below agent where we add the `SerperTool` to the agent to give it the ability to search on Google.

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

        protected function resolveTools(): array  // [!code focus:4]
        {
            return [new SerperTool];
        }
    }
```

Synapse comes packaged with tools you can use to read about them check out the [packaged tools section](/tools/packaged-tools). However if you want to build your own tools check out the [anatomy of a tool section](/tools/anatomy-of-a-tool). 
