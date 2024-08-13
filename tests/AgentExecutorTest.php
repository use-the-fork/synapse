<?php

declare(strict_types=1);

use UseTheFork\Synapse\Agent;
use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;
use UseTheFork\Synapse\Memory\DatabaseMemory;
use UseTheFork\Synapse\Prompts\SimplePrompt;
use UseTheFork\Synapse\Tools\SerperTool;

it('connects to OpenAI', function () {
    $agent = new Agent(
        integration: new OpenAIConnector(),
        prompt: new SimplePrompt(),
        memory: new DatabaseMemory(),
        tools: [
            new SerperTool(),
        ]
    );
    $agent->setOutputRules();

    $t = $agent->__invoke(['query' => 'search google for the current president of the united states.']);
    dd($t);
})->skip();
