<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Services\Firecrawl\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class FirecrawlRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $url,
        public readonly string $extractionPrompt
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v0/scrape';
    }

    public function defaultBody(): array
    {
        return [
            'url' => $this->url,
            'extractorOptions' => [
                'mode' => 'llm-extraction',
                'extractionPrompt' => "detailed markdown list related to **{$this->extractionPrompt}** if no relevant content is found return `No Relevant Content On Page` DO NOT respond with only a URL or Link.",
                'extractionSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'result' => ['type' => 'string'],
                    ],
                    'required' => [
                        'result',
                    ],
                ],
            ],
        ];
    }
}
