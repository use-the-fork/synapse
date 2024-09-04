<?php

    namespace UseTheFork\Synapse\Integrations\Connectors\Claude\Requests;

    use Saloon\Contracts\Body\HasBody;
    use Saloon\Enums\Method;
    use Saloon\Http\Request;
    use Saloon\Http\Response;
    use Saloon\Traits\Body\HasJsonBody;
    use UseTheFork\Synapse\Integrations\Enums\ResponseType;
    use UseTheFork\Synapse\Integrations\ValueObjects\Message;
    use UseTheFork\Synapse\Integrations\ValueObjects\Response as IntegrationResponse;
    use UseTheFork\Synapse\Tools\ValueObjects\ToolCallValueObject;

    class ValidateOutputRequest extends Request implements HasBody
    {
        use HasJsonBody;
        private string $system = '';

        /**
         * The HTTP method
         */
        protected Method $method = Method::POST;

        public function __construct(
            public readonly Message $prompt,
            public readonly array $extraAgentArgs = []
        ) {}

        /**
         * The endpoint
         */
        public function resolveEndpoint(): string
        {
            return '/messages';
        }

        /**
         * Data to be sent in the body of the request
         */
        public function defaultBody(): array
        {
            $model = config('synapse.integrations.claude.model');

            $payload = [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $this->prompt->content(),
                    ]
                ],
                'system' => "### Instruction\nRewrite user-generated content to adhere to the specified format. DO NOT EXPLAIN.",
                'max_tokens' => 4096
            ];

            return [
                ...$payload,
                ...$this->extraAgentArgs,
            ];

        }

        public function createDtoFromResponse(Response $response): IntegrationResponse
        {
            $data = $response->array();
            $message = [];

            $message['role'] = 'assistant';
            $message['finish_reason'] = ResponseType::STOP;
            foreach ($data['content'] as $choice) {
                if ($choice['type'] === 'text') {
                    $message['content'] = $choice['text'];
                }
            }

            return IntegrationResponse::makeOrNull($message);
        }
    }
