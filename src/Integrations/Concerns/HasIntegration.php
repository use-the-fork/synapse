<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Concerns;

use Saloon\Http\Connector;
use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;
use UseTheFork\Synapse\Integrations\OpenAI\Requests\ChatRequest;

trait HasIntegration
{
    /**
     * The integration that this Model should use
     */
    protected Connector $integration;

    /**
     * The chat request class this model uses. A new one is created every time.
     */
    protected string $chatRequestClass;

    /**
     * returns the memory type this Agent should use.
     */
    protected function registerIntegration(): array
    {
        return [
          'connector' => OpenAIConnector::class,
          'chatRequest' => ChatRequest::class,
        ];
    }

    /**
     * Resolve the observe class names from the attributes.
     */
    protected function initializeIntegration(): void
    {
        $integration = $this->registerIntegration();
        $this->integration = new $integration['connector']();
        $this->chatRequestClass = $integration['chatRequest'];
    }

  private function getChatRequest(array $prompt, $extraAgentArgs): object
  {
    return new $this->chatRequestClass($prompt, $this->registered_tools, $extraAgentArgs);
  }

}
