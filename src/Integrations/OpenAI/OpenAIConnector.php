<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\OpenAI;


use OpenAI;
use OpenAI\Client;
use UseTheFork\Synapse\Integrations\Contracts\ModelIntegration;
use UseTheFork\Synapse\Integrations\Exceptions\InvalidEnvironmentException;
use UseTheFork\Synapse\ValueObject\MessageValueObject;
use UseTheFork\Synapse\ValueObject\ToolCallValueObject;

class OpenAIConnector implements ModelIntegration
{

  private string $apiKey;
  private Client $client;

  public function __construct(
    public string $model = 'gpt-4-turbo',
    public float $temperature = 1,
    public int|null $maxTokens = null,
  ) {
    $this->apiKey = config('synapse.openapi_key');
    $this->validateEnvironment();
    $this->client = OpenAI::client($this->apiKey);
  }

  public function __invoke(string $systemPrompt, array $messages, array $tools = []): MessageValueObject
  {
    $payload = $this->generateRequestBody($systemPrompt, $messages, $tools);

    return $this->createDtoFromResponse($this->client->chat()->create($payload));
  }


  public function validateEnvironment(): void
  {
    if(!$this->apiKey){
      throw new InvalidEnvironmentException("OPENAI_API_KEY is missing.");
    }
  }

  public function createDtoFromResponse($response): MessageValueObject
  {
    $data = $response->toArray();
    $message = $data['choices'][0]['message'] ?? [];
    $tools = collect([]);
    if (isset($message['tool_calls'])) {
      foreach ($message['tool_calls'] as $toolCall) {
        $tools->push(ToolCallValueObject::make($toolCall));
      }
      $message['tool_calls'] = $tools->toArray();
    }

    return MessageValueObject::makeOrNull($message);
  }

  /**
   * Data to be sent in the body of the request
   */
  public function generateRequestBody(string $systemPrompt, array $messages, array $tools = []): array
  {
    $payload = [[
                   'role' => 'system',
                   'content' => $systemPrompt,
                 ]];

    foreach ($messages as $message) {

      $messagePayload = [
        'role' => $message['role'],
        'content' => $message['content'],
      ];
      if($message['tool_call_id']){
        $messagePayload['tool_call_id'] = $message['tool_call_id'];
      }
      $payload[] = $messagePayload;

      if ($message['tool_calls']) {
        $payload[count($payload) - 1]['tool_calls'] = $message['tool_calls'];
      }
    }

    $payload = [
      'model' => $this->model,
      'messages' => $payload,
    ];

    if (! empty($tools)) {
      foreach ($tools as $tool){
        $payload['tools'][] = $tool['definition'];
      }
    }

    return $payload;
  }

}
