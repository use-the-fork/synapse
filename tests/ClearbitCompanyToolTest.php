<?php

declare(strict_types=1);

  use UseTheFork\Synapse\AgentExecutor;
  use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;
  use UseTheFork\Synapse\Memory\DatabaseMemory;
  use UseTheFork\Synapse\OutputParsers\JsonOutputParser;
  use UseTheFork\Synapse\OutputParsers\StringOutputParser;
  use UseTheFork\Synapse\SystemPrompts\MultiQueryRetrieverSystemPrompt;
  use UseTheFork\Synapse\SystemPrompts\ResearchCompanySystemPrompt;
  use UseTheFork\Synapse\SystemPrompts\SimpleSystemPrompt;
  use UseTheFork\Synapse\Tools\ClearbitCompanyTool;
  use UseTheFork\Synapse\Tools\SearchGoogleTool;

it('can search clearbit', function () {


    $agent = new AgentExecutor(
      integration: new OpenAIConnector(),
      systemPrompt: new ResearchCompanySystemPrompt(),
      memory: new DatabaseMemory(),
      outputParser: new StringOutputParser(),
      tools: [
        new ClearbitCompanyTool(env("CLEARBIT_API_KEY"))
             ]
    );
    $t = $agent->__invoke('Products strengths and weaknesses of InVue Security Products Charlotte, NC');
    dd($t);
});
