# Integrations

Synapse currently supports two integrations: OpenAI and Claude. To use either, add the relevant API key to your `.env` file.

```dotenv
OPENAI_API_KEY=
ANTHROPIC_API_KEY=
```

Then, implement the chosen integration in your agent's `resolveIntegration` method.

## OpenAI

```php
use UseTheFork\Synapse\Integrations\OpenAIIntegration;  

...

public function resolveIntegration(): Integration
{
    return new OpenAIIntegration;
}
```

## Claude

```php
use UseTheFork\Synapse\Integrations\ClaudeIntegration;  

...

public function resolveIntegration(): Integration
{
    return new ClaudeIntegration;
}
```
