<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UseTheFork\Synapse\Contracts\Tool;
use UseTheFork\Synapse\Exceptions\MissingApiKeyException;
use UseTheFork\Synapse\Services\Crunchbase\Requests\CrunchbaseRequest;
use UseTheFork\Synapse\Tools\BaseTool;
use UseTheFork\Synapse\Tools\CrunchbaseTool;

test('Requires API Key', function (): void {
    $tool = new CrunchbaseTool;
    $tool->handle('siteimprove');
})->throws(MissingApiKeyException::class);

test('Send Request', function (): void {

    MockClient::global([
        CrunchbaseRequest::class => MockResponse::fixture('tools/crunchbase'),
    ]);

    $tool = new CrunchbaseTool('abc');
    $result = $tool->handle('siteimprove');
    expect($result !== '' && $result !== '0')->toBeTrue();
});

test('Architecture', function (): void {

    expect(CrunchbaseTool::class)
        ->toExtend(BaseTool::class)
        ->toImplement(Tool::class);

});
