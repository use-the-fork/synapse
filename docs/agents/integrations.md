# Integrations

Synapse currently supports three integrations: OpenAI, Claude, and Ollama. To use them, add the relevant API key to your `.env` file.

```dotenv
OLLAMA_BASE_URL=https://foo.bar:1234
OPENAI_API_KEY=
ANTHROPIC_API_KEY=
```

Then, either implement the chosen integration in your agent's `resolveIntegration` method or set the default integration in your synapse config.

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

## Ollama

```php
use UseTheFork\Synapse\Integrations\OllamaIntegration;  

...

public function resolveIntegration(): Integration
{
    return new OllamaIntegration;
}
```
