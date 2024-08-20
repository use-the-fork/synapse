<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\OpenAI;

use OpenAI;
use OpenAI\Client;
use UseTheFork\Synapse\Integrations\Contracts\Integration;
use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\Exceptions\InvalidEnvironmentException;
use UseTheFork\Synapse\Integrations\ValueObjects\Response;
use UseTheFork\Synapse\Tools\ValueObjects\ToolCallValueObject;

class OpenAIConnector implements Integration
{
    private string $apiKey;

    private Client $client;

    public function __construct(
        public ?string $model = null,
        public float $temperature = 1,
        public ?int $maxTokens = null,
    ) {
        $this->apiKey = config('synapse.integrations.openai.key');
        if(empty($this->model)){
          $this->model = config('synapse.integrations.openai.model');
        }

        $this->validateEnvironment();
        $this->client = OpenAI::client($this->apiKey);
    }

    public function handle(array $prompt, ?array $tools = [], ?array $extraAgentArgs = []): Response
    {
        $payload = $this->generateRequestBody($prompt, $tools, $extraAgentArgs);
        $response = $this->client->chat()->create($payload);

        return $this->createDtoFromResponse($response);
    }

    public function validateEnvironment(): void
    {
        if (! $this->apiKey) {
            throw new InvalidEnvironmentException('OPENAI_API_KEY is missing.');
        }
    }

    public function createDtoFromResponse($response): Response
    {
        $data = $response->toArray();
        $message = $data['choices'][0]['message'] ?? [];
        $message['finish_reason'] = $data['choices'][0]['finish_reason'] ?? '';
        $tools = collect([]);
        if (isset($message['tool_calls'])) {
            foreach ($message['tool_calls'] as $toolCall) {
                $tools->push(ToolCallValueObject::make($toolCall));
            }
            $message['tool_calls'] = $tools->toArray();
        }

        return Response::makeOrNull($message);
    }

    /**
     * Data to be sent in the body of the request
     */
    public function generateRequestBody(array $messages, ?array $tools = [], ?array $extraAgentArgs = []): array
    {

      $payload = [];
      foreach ($messages as $message){

        $message = $message->toArray();
        $payloadMessage = [
          'role' => $message['role'],
          'content' => $message['content'],
          ...$extraAgentArgs
        ];

        if(!empty($message['tool_call_id'])){
            if($message['role'] == Role::ASSISTANT){
              $payloadMessage['tool_calls'][] = [
                'id' => $message['tool_call_id'],
                'type' => 'function',
                'function' => [
                  'name' => $message['tool_name'],
                  'arguments' => $message['tool_arguments'],
                ],
              ];
            } else {
              # we know this is a tool response
              $payloadMessage['tool_call_id'] = $message['tool_call_id'];
            }
        }

        $payload[] = $payloadMessage;
      }

        $payload = [
            'model' => $this->model,
            'messages' => $payload,
        ];

        if (! empty($tools)) {
            foreach ($tools as $tool) {
                $payload['tools'][] = $tool['definition'];
            }
        }

        return $payload;
    }
}
