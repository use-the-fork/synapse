<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agent;
  use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;
  use UseTheFork\Synapse\Memory\DatabaseMemory;
  use UseTheFork\Synapse\OutputParsers\JsonOutputParser;
  use UseTheFork\Synapse\OutputParsers\StringOutputParser;
  use UseTheFork\Synapse\Prompts\MultiQueryRetrieverPrompt;
  use UseTheFork\Synapse\Prompts\SimplePrompt;
  use UseTheFork\Synapse\Tools\SearchGoogleTool;

it('connects to OpenAI', function () {
  $agent = new Agent(
      integration: new OpenAIConnector(),
      prompt: new SimplePrompt(),
      memory: new DatabaseMemory(),
      tools: [
        new SearchGoogleTool()
       ]
    );
  $agent->setOutputRules();

    $t = $agent->__invoke(['query' => 'search google for the current president of the united states.']);
    dd($t);
})->skip();

it('can parse JSON output', function () {

    $expectedOutput = [
      'Question 1',
      'Question 2',
      'Question 3',
      'Question 4',
      'Question 5',
    ];


    $agent = new Agent(
      integration: new OpenAIConnector(),
      prompt: new MultiQueryRetrieverPrompt(),
      memory: new DatabaseMemory(),
      outputParser: new JsonOutputParser($expectedOutput),
      tools: []
    );
    $t = $agent->__invoke(['query' => 'Products strengths and weaknesses of InVue Security Products Charlotte, NC']);
    dd($t);
})->skip();
