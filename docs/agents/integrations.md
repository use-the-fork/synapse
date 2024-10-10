# Integrations

Synapse currently supports two integrations OpenAI and Claude. Depending on which you would like to use add the appropriate API key to your .env file.

```dotenv
OPENAI_API_KEY=
ANTHROPIC_API_KEY=
```

and then add the integration to your agents `resolveIntegration` method.

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

