<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use UseTheFork\Synapse\Integrations\Enums\ResponseType;
use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Integrations\ValueObjects\Response as IntegrationResponse;

class ValidateOutputRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly Message $prompt,
        public readonly array $extraAgentArgs = []
    ) {}

    public function resolveEndpoint(): string
    {
        return '/chat/completions';
    }

    public function defaultBody(): array
    {
        $model = config('synapse.integrations.openai.model');

        $userMessage = "### Instruction\nRewrite user-generated content to adhere to the specified format. DO NOT EXPLAIN.\n\n{$this->prompt->content()}";

        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => Role::USER,
                    'content' => $userMessage,
                ],
            ],
        ];

        return [
            ...$payload,
            ...$this->extraAgentArgs,
        ];
    }

    public function createDtoFromResponse(Response $response): IntegrationResponse
    {
        $data = $response->array();
        $message = $data['choices'][0]['message'] ?? [];
        $message['finish_reason'] = ResponseType::STOP;

        return IntegrationResponse::makeOrNull($message);
    }
}
