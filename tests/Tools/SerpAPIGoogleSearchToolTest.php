<?php

  declare(strict_types=1);

  use Saloon\Http\Faking\MockClient;
  use Saloon\Http\Faking\MockResponse;
  use UseTheFork\Synapse\Services\SerpApi\Requests\SerpApiSearchRequest;
  use UseTheFork\Synapse\Tools\BaseTool;
  use UseTheFork\Synapse\Tools\Contracts\Tool;
  use UseTheFork\Synapse\Tools\Exceptions\MissingApiKeyException;
  use UseTheFork\Synapse\Tools\SerpAPIGoogleSearchTool;

  test('Requires API Key', function () {
    $tool = new SerpAPIGoogleSearchTool();
    $tool->handle('current President of the United States');
  })->throws(MissingApiKeyException::class);

  test('Send Request', function () {

    MockClient::global([
                         SerpApiSearchRequest::class => MockResponse::fixture('tools/serpapi'),
                       ]);

    $tool = new SerpAPIGoogleSearchTool('c0c2b81d7e3bf0970911f8f74c43a11c77e2e3b100b9e5af809ed5de246ba7e1');
    $result = $tool->handle('current President of the United States');
    expect(!empty($result))->toBeTrue();
  });

  test('Architecture', function () {

    expect(SerpAPIGoogleSearchTool::class)
      ->toExtend(BaseTool::class)
      ->toImplement(Tool::class);

  });
