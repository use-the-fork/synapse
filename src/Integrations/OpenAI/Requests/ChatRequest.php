<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\OpenAI\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Response as IntegrationResponse;
use UseTheFork\Synapse\Tools\ValueObjects\ToolCallValueObject;

class ChatRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly array $prompt,
        public readonly array $tools,
        public readonly array $extraAgentArgs = []
    ) {}

    public function resolveEndpoint(): string
    {
        return '/chat/completions';
    }

    public function defaultBody(): array
    {
        $model = config('synapse.integrations.openai.model', 'gpt-4-turbo');

        $payload = [
            'model' => $model,
            'messages' => $this->formatMessages($this->prompt),
        ];

        if (! empty($this->tools)) {
            foreach ($this->tools as $tool) {
                $payload['tools'][] = $tool['definition'];
            }
        }

        return [
            ...$payload,
            ...$this->extraAgentArgs,
        ];
    }

    private function formatMessages($messages): array
    {

        $payload = [];
        foreach ($messages as $message) {

            $message = $message->toArray();
            $payloadMessage = [
                'role' => $message['role'],
                'content' => $message['content'],
            ];

            if (! empty($message['tool_call_id'])) {
                if ($message['role'] == Role::ASSISTANT) {
                    $payloadMessage['tool_calls'][] = [
                        'id' => $message['tool_call_id'],
                        'type' => 'function',
                        'function' => [
                            'name' => $message['tool_name'],
                            'arguments' => $message['tool_arguments'],
                        ],
                    ];
                } else {
                    // we know this is a tool response
                    $payloadMessage['tool_call_id'] = $message['tool_call_id'];
                }
            }

            $payload[] = $payloadMessage;
        }

        return $payload;
    }

    public function createDtoFromResponse(Response $response): IntegrationResponse
    {
        $data = $response->array();
        $message = $data['choices'][0]['message'] ?? [];
        $message['finish_reason'] = $data['choices'][0]['finish_reason'] ?? '';
        $tools = collect([]);
        if (isset($message['tool_calls'])) {
            foreach ($message['tool_calls'] as $toolCall) {
                $tools->push(ToolCallValueObject::make($toolCall));
            }
            $message['tool_calls'] = $tools->toArray();
        }

        return IntegrationResponse::makeOrNull($message);
    }
}
