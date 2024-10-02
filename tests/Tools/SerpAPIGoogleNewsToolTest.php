<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\SerpApi\Requests\SerpApiSearchRequest;
use UseTheFork\Synapse\Tools\BaseTool;
use UseTheFork\Synapse\Tools\SerpAPIGoogleNewsTool;

test('Requires API Key', function (): void {
    $tool = new SerpAPIGoogleNewsTool;
    $tool->handle('current President of the United States');
})->throws(MissingApiKeyException::class);

test('Send Request', function (): void {

    MockClient::global([
        SerpApiSearchRequest::class => MockResponse::fixture('tools/serpapi-google-news'),
    ]);

    $tool = new SerpAPIGoogleNewsTool('abc');
    $result = $tool->handle('apple stock');
    expect($result !== '' && $result !== '0')->toBeTrue();
});

test('Architecture', function (): void {

    expect(SerpAPIGoogleNewsTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
