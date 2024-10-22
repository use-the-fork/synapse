<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Integrations\Connectors\Ollama\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use UseTheFork\Synapse\ValueObject\EmbeddingResponse;

class EmbeddingsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $input,
        public readonly array $extraAgentArgs = []
    ) {}

    public function createDtoFromResponse(Response $response): EmbeddingResponse
    {
        $data = $response->array();

        return EmbeddingResponse::makeOrNull($data);
    }

    public function defaultBody(): array
    {
        $model = config('synapse.integrations.ollama.embedding_model');

        return [
            'model' => $model,
            'input' => $this->input,
            ...$this->extraAgentArgs,
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/embed';
    }
}
