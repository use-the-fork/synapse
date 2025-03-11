<?php

    namespace UseTheFork\Synapse\Tests\Fixtures\OpenAi;

    use Saloon\Http\Faking\Fixture;

    class OpenAiFixture extends Fixture
    {

        protected function defineSensitiveHeaders(): array
        {
            return [
                'openai-organization' => 'REDACTED',
                'x-request-id' => 'REDACTED',
                "Set-Cookie" => 'REDACTED',
                'CF-RAY' => 'REDACTED',
            ];
        }
    }
