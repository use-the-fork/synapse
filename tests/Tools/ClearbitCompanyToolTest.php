<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Clearbit\Requests\ClearbitCompanyRequest;
use UseTheFork\Synapse\Tools\BaseTool;
use UseTheFork\Synapse\Tools\ClearbitCompanyTool;

test('Requires API Key', function (): void {
    $tool = new ClearbitCompanyTool;
    $tool->handle('google.com');
})->throws(MissingApiKeyException::class);

test('Send Request', function (): void {

    MockClient::global([
        ClearbitCompanyRequest::class => MockResponse::fixture('tools/clearbit'),
    ]);

    $tool = new ClearbitCompanyTool('abc123');
    $result = $tool->handle('google.com');
    expect($result !== '' && $result !== '0')->toBeTrue();
});

test('Architecture', function (): void {

    expect(ClearbitCompanyTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
