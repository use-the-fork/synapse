<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

test('can handle image input', function () {

    MockClient::global([
        ChatRequest::class => MockResponse::fixture('agents/image-agent/message-1'),
    ]);

    class ImageAgentTestAgent extends Agent
    {
        protected string $promptView = 'synapse::Prompts.SimplePrompt';

        protected function registerOutputRules(): array
        {
            return [
                OutputRule::make([
                    'name' => 'answer',
                    'rules' => 'required|string',
                    'description' => 'your final answer to the query.',
                ]),
            ];
        }
    }

    $agent = new ImageAgentTestAgent;
    $imageInfo = ['url' => 'data:image/png;base64,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'];
    $agentResponse = $agent->handle(['input' => 'return the text in the image.', 'image' => $imageInfo]);

    expect($agentResponse)->toBeArray()
        ->and($agentResponse)->toHaveKey('answer')
        ->and($agentResponse['answer'])->toContain('You can also pass params to the loader.');
});