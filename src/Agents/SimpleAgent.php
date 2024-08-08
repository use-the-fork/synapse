<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use OpenAI;
use OpenAI\Client;
use UseTheFork\Synapse\Models\Agent;

class SimpleAgent extends BaseAgent
{
    protected Client $client;

    protected Agent $agent;

    public function __construct(
        public array $tools = []
    ) {

        $this->agent = new Agent([
            'type' => __CLASS__,
            'model' => 'gpt-4-turbo',
            'prompt' => 'You are a helpful assistant.',
            'tools' => $tools,
        ]);

        $this->agent->save();

        $apiKey = config('synapse.openapi_key');
        $this->client = OpenAI::client($apiKey);
    }

    public function invoke(mixed $input): string
    {
        return $this->getAnswer($input);
    }
}
