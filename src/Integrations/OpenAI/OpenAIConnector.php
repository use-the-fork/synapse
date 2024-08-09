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
        public ?int $maxTokens = null,
    ) {
        $this->apiKey = config('synapse.openapi_key');
        $this->validateEnvironment();
        $this->client = OpenAI::client($this->apiKey);
    }

    public function __invoke(string $prompt, array $tools = []): MessageValueObject
    {
        $payload = $this->generateRequestBody($prompt, $tools);
        $response = $this->client->chat()->create($payload);

        return $this->createDtoFromResponse($response);
    }

    public function validateEnvironment(): void
    {
        if (! $this->apiKey) {
            throw new InvalidEnvironmentException('OPENAI_API_KEY is missing.');
        }
    }

    public function createDtoFromResponse($response): MessageValueObject
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

        return MessageValueObject::makeOrNull($message);
    }

    /**
     * Data to be sent in the body of the request
     */
    public function generateRequestBody(string $prompt, array $tools = []): array
    {
        $payload = [[
            'role' => 'user',
            'content' => $prompt,
        ]];

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
