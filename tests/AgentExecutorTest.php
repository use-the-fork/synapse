<?php

declare(strict_types=1);

  use UseTheFork\Synapse\AgentExecutor;
  use UseTheFork\Synapse\Agents\SimpleAgent;
  use UseTheFork\Synapse\Integrations\OpenAI\OpenAIConnector;
  use UseTheFork\Synapse\Memory\DatabaseMemory;
  use UseTheFork\Synapse\SystemPrompts\ChatPromptTemplate;
  use UseTheFork\Synapse\SystemPrompts\SimpleSystemSystemPrompt;
  use UseTheFork\Synapse\Tools\SearchGoogleTool;

it('connects to OpenAI', function () {
    $simpleAgent = new AgentExecutor(
      integration: new OpenAIConnector(),
      systemPrompt: new SimpleSystemSystemPrompt(),
      memory: new DatabaseMemory(),
      tools: [
        new SearchGoogleTool()
       ]
    );
    $t = $simpleAgent->__invoke('search google for the current president of the united states.');

    dd($t);
});
