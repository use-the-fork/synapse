# Validates Output Schema Trait

It's often desirable to have an agent respond in a specific format. To make this task easy Synapse uses Laravels built in validator, a view snippit, and a validation loop.

To get started add the `HasOutputSchema` interface, the `ValidatesOutputSchema` trait, and the `resolveOutputSchema` method to your agent. From here you can add an array of `SchemaRule` value objects.

Keep in mind you will also need to include the `@include('synapse::Parts.OutputSchema')` in your blade view so the agent knows what schema to follow.

In the below example we have included our interface, trait, and method. Then we have `resolveOutputSchema` return one `SchemaRule`. That has the name of the key that should be returned. What is expected in the description and the Validator rules that must be followed.

```php
<?php

use UseTheFork\Synapse\Agents\Agent;
use UseTheFork\Synapse\Agents\Integrations\OpenAI\OpenAIIntegration;
use UseTheFork\Synapse\Contracts\Agent\HasOutputSchema;
use UseTheFork\Synapse\Traits\Agent\ValidatesOutputSchema;
use UseTheFork\Synapse\ValueObject\SchemaRule;

class SimpleAgent extends Agent implements HasOutputSchema  // [!code focus]
{

    use ValidatesOutputSchema; // [!code focus]

    public function resolvePromptView(): string
    {
        return 'synapse::Prompts.SimpleAgentPrompt';
    }

    public function resolveIntegration(): string
    {
        return new OpenAIIntegration();
    }

    public function resolveOutputSchema(): array // [!code focus:10]
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
The `SchemaRule` Value Object takes three inputs:
* name: The key that will be used in the return.
* rules: Laravel validator rules to be applied to this input.
* description: What should be the agent put in this field.

Keep in mind you can use as many `SchemaRule` objects as you would like. For example below we ask for the output to be an array of `pdfs` objects. Each with a link, title, category, and product.

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
              'description' => 'The Title of the PDF (usually the link text) in title case.',
          ]),
          SchemaRule::make([
              'name' => 'pdfs.*.category',
              'rules' => 'required|string',
              'description' => 'One of `Order Form`, `Technical Data`, `Catalog`, `Parts Manual`, `Brochure`, `Template`, `Miscellaneous`',
          ]),
          SchemaRule::make([
              'name' => 'pdfs.*.product',
              'rules' => 'sometimes|string',
              'description' => 'If this PDF relates to a specific product put the name here.',
          ])
      ];
  }

```
