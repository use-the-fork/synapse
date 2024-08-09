<?php

declare(strict_types=1);

  use UseTheFork\Synapse\AgentExecutor;
  use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;
  use UseTheFork\Synapse\Memory\DatabaseMemory;
  use UseTheFork\Synapse\OutputParsers\JsonOutputParser;
  use UseTheFork\Synapse\OutputParsers\StringOutputParser;
  use UseTheFork\Synapse\SystemPrompts\MultiQueryRetrieverSystemPrompt;
  use UseTheFork\Synapse\SystemPrompts\SimpleSystemPrompt;
  use UseTheFork\Synapse\Tools\SearchGoogleTool;

it('connects to OpenAI', function () {
  $agent = new AgentExecutor(
      integration: new OpenAIConnector(),
      systemPrompt: new SimpleSystemPrompt(),
      memory: new DatabaseMemory(),
      outputParser: new StringOutputParser(),
      tools: [
        new SearchGoogleTool()
       ]
    );
    $t = $agent->__invoke('search google for the current president of the united states.');
})->skip();

it('can parse JSON output', function () {

    $expectedOutput = [
      'Question 1',
      'Question 2',
      'Question 3',
      'Question 4',
      'Question 5',
    ];


    $agent = new AgentExecutor(
      integration: new OpenAIConnector(),
      systemPrompt: new MultiQueryRetrieverSystemPrompt(),
      memory: new DatabaseMemory(),
      outputParser: new JsonOutputParser($expectedOutput),
      tools: []
    );
    $t = $agent->__invoke('Products strengths and weaknesses of InVue Security Products Charlotte, NC');
    dd($t);
});
