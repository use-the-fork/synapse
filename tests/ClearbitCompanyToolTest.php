<?php

declare(strict_types=1);

  use UseTheFork\Synapse\AgentExecutor;
  use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;
  use UseTheFork\Synapse\Memory\DatabaseMemory;
  use UseTheFork\Synapse\OutputParsers\JsonOutputParser;
  use UseTheFork\Synapse\OutputParsers\StringOutputParser;
  use UseTheFork\Synapse\Prompts\MultiQueryRetrieverPrompt;
  use UseTheFork\Synapse\Prompts\ResearchCompanyPrompt;
  use UseTheFork\Synapse\Prompts\SimplePrompt;
  use UseTheFork\Synapse\Tools\ClearbitCompanyTool;
  use UseTheFork\Synapse\Tools\SearchGoogleTool;

it('can search clearbit', function () {


    $agent = new AgentExecutor(
      integration: new OpenAIConnector(),
      prompt: new ResearchCompanyPrompt(),
      memory: new DatabaseMemory(),
      outputParser: new StringOutputParser(),
      tools: [
        new ClearbitCompanyTool(env("CLEARBIT_API_KEY")),
        new SearchGoogleTool()
             ]
    );
    $t = $agent->__invoke(['query' => 'Products strengths and weaknesses of InVue Security Products Charlotte, NC']);
    dd($t);
})->only();
