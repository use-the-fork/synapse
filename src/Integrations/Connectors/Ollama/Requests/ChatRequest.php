<?php

namespace UseTheFork\Synapse\Integrations\Connectors\Ollama\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use UseTheFork\Synapse\Constants\Role;
use UseTheFork\Synapse\Enums\FinishReason;
use UseTheFork\Synapse\ValueObject\Message;

class ChatRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method
     */
    protected Method $method = Method::POST;
    private string $system = '';

    public function __construct(
        public readonly array $prompt,
        public readonly array $tools,
        public readonly array $extraAgentArgs = []
    ) {}

    public function createDtoFromResponse(Response $response): Message
    {
        $data = $response->array();
        $message = [];

        $message['role'] = 'assistant';

        if(empty($data['message']['tool_calls'])){
            $message['finish_reason'] = FinishReason::STOP->value;
            $message['content'] = $data['message']['content'];
        } else {
            // Tool call
            $message['finish_reason'] = FinishReason::TOOL_CALL->value;
            $message['content'] = '';

            # To keep tool call ID's the same in our cache we create a md5 hash of the tool call and use that as the ID
            $message['tool_call_id'] = md5(json_encode($data['message']['tool_calls'][0]));
            $message['tool_name'] = $data['message']['tool_calls'][0]['function']['name'];
            $message['tool_arguments'] = json_encode($data['message']['tool_calls'][0]['function']['arguments']);
            $message['role'] = Role::TOOL;
        }

        return Message::make($message);
    }

    /**
     * Data to be sent in the body of the request
     */
    public function defaultBody(): array
    {
        $model = config('synapse.integrations.ollama.chat_model');

        $payload = [
            'model' => $model,
            'messages' => $this->formatMessages(),
            'stream' => false,
            'raw' => true,
        ];

//        if(!empty($this->system)){
//            $payload['system'] = $this->system;
//        }

        if ($this->tools !== []) {
            $payload['tools'] = $this->formatTools();
        }

        return [
            ...$payload,
            ...$this->extraAgentArgs,
        ];

    }

    private function formatMessages(): array
    {

        $payload = collect();
        foreach ($this->prompt as $message) {
            switch ($message->role()) {
                case Role::SYSTEM:
                    # O seems to do better when the 'system' prompt starts as a user prompt... I'll take it.
                    $payload->push([
                                       'role' => 'user',
                                       'content' => $message->content(),
                                   ]);
                    break;
                case Role::TOOL:
                    $toolPayload = $this->formatToolMessage($message);
                    $payload->push(...$toolPayload);
                    break;
                default:
                    $payload->push([
                        'role' => $message->role(),
                        'content' => $message->content(),
                    ]);
                    break;
            }
        }

        return $payload->values()->toArray();
    }

    private function formatToolMessage(Message $message): array
    {
        $message = $message->toArray();

        // Claude requires tool responses to be multipart agent and user responses.
        $payload[] = [
            'role' => 'assistant',
            'content' => $message['content'],
            'tool_calls' => [
                [
                    'id' => $message['tool_call_id'],
                    'type' => 'function',
                    'function' => [
                        'name' => $message['tool_name'],
                        'arguments' => json_decode($message['tool_arguments'], true),
                    ],
                ]
            ],
        ];
        $payload[] = [
            'role' => 'tool',
            'content' => $message['tool_content'],
        ];

        return $payload;
    }

    private function formatTools(): array
    {
        $formattedTools = [];
        foreach ($this->tools as $key => $tool) {

            $tool = $tool['definition'];

            if (is_array($tool) && isset($tool['function'])) {
                $formattedTools[] = [
                    'type' => 'function',
                    'function' => [
                        'name' => $tool['function']['name'] ?? $key,
                        'description' => $tool['function']['description'] ?? '',
                        'parameters' => $tool['function']['parameters'] ?? [],
                    ],
                ];
            }
        }

        return $formattedTools;

    }

    /**
     * The endpoint
     */
    public function resolveEndpoint(): string
    {
        return '/chat';
    }
}
