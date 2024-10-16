# Prebuilt Agents

Synapse includes several prebuilt agents that you can use right out of the box. If you have set a `default` integration in your `synapse` config, simply include the agent in your code, and you're all set.

```php
<?php

use UseTheFork\Synapse\Agents\MultiQueryRetrieverAgent;

$agent = new MultiQueryRetrieverAgent;
$agentResponse = $agent->handle(['queryCount' => '5', 'input' => 'What gym activities do you recommend for heart health?']);
```

If you're using a different integration or creating your own, you'll need to extend the prebuilt agent and override the `resolveIntegration` method to return your preferred integration.
