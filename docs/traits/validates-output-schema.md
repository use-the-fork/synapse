# Validates Output Schema Trait

To ensure that an agent responds in a specific format, Synapse leverages Laravel's built-in validator, a view snippet, and a validation loop. This is achieved using the `HasOutputSchema` interface, the `ValidatesOutputSchema` trait, and the `resolveOutputSchema` method.

To start, add the `HasOutputSchema` interface and the `ValidatesOutputSchema` trait to your agent, and implement the `resolveOutputSchema` method. This method should return an array of `SchemaRule` value objects that define the validation rules for the agent's response.

Ensure you include `@include('synapse::Parts.OutputSchema')` in your Blade view so that the agent adheres to the specified schema.

In the example below, the agent defines one `SchemaRule` for the `answer` field, describing its requirements and validation rules.

```php
<?php

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Agents\Integrations\OpenAI\OpenAIIntegration;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class SimpleAgent extends Agent implements HasOutputSchema
{
    use ValidatesOutputSchema;

    public function resolvePromptView(): string
    {
        return 'synapse::Prompts.SimpleAgentPrompt';
    }

    public function resolveIntegration(): string
    {
        return new OpenAIIntegration();
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

## The `SchemaRule` Value Object
The `SchemaRule` Value Object takes three parameters:

- **name**: The key to be returned in the output.
- **rules**: Laravel validation rules to apply.
- **description**: Describes what should be placed in this field.

You can define multiple `SchemaRule` objects. For instance, the example below requires an array of `pdfs` objects, each with specific attributes like link, title, category, and product.

```php
public function resolveOutputSchema(): array
{
    return [
        SchemaRule::make([
            'name' => 'pdfs.*',
            'rules' => 'sometimes|array',
            'description' => 'An array of PDF links extracted from the website content.',
        ]),
        SchemaRule::make([
            'name' => 'pdfs.*.link',
            'rules' => 'required|url',
            'description' => 'The link URL for the PDF.',
        ]),
        SchemaRule::make([
            'name' => 'pdfs.*.title',
            'rules' => 'required|string',
            'description' => 'The title of the PDF (usually the link text) in title case.',
        ]),
        SchemaRule::make([
            'name' => 'pdfs.*.category',
            'rules' => 'required|string',
            'description' => 'One of `Order Form`, `Technical Data`, `Catalog`, `Parts Manual`, `Brochure`, `Template`, `Miscellaneous`.',
        ]),
        SchemaRule::make([
            'name' => 'pdfs.*.product',
            'rules' => 'sometimes|string',
            'description' => 'If this PDF relates to a specific product, specify the name here.',
        ])
    ];
}
```
