<?php

declare(strict_types=1);

  use UseTheFork\Synapse\Agents\ProfessionalEditorAgent;
  use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

  it('can handle input rules being added.', function () {
  $agent = new ProfessionalEditorAgent();
  $agentResponse = $agent->handle(['query' => 'Is there an advantage in using multiple messages in the chat completions. Or can I combine them all as a single user message I send?']);

  expect($agentResponse)->toBeArray()->and($agentResponse)->toHaveKey('meaning');

    $iterativePrompt = $agentResponse;

    $agent->setOutputRules([
       OutputRule::make([
                          'name'       => 'draft_1',
                          'rules'       => 'required|string',
                          'description' => 'your updated draft based on the users input.'
                        ]),
      OutputRule::make([
                         'name'       => 'reflection_1',
                         'rules'       => 'required|string',
                         'description' => 'your reflection of the draft.'
                       ])
    ]);

    $agentResponse = $agent->handle($agentResponse);

    $iterativePrompt = [
      ...$iterativePrompt,
      ...$agentResponse
    ];

    $agent->setOutputRules([
                             OutputRule::make([
                                                'name'       => 'draft_2',
                                                'rules'       => 'required|string',
                                                'description' => 'your updated draft based draft 1 and the user input.'
                                              ]),
                             OutputRule::make([
                                                'name'       => 'reflection_2',
                                                'rules'       => 'required|string',
                                                'description' => 'your reflection of the draft.'
                                              ])
                           ]);

    $agentResponse = $agent->handle($agentResponse);

    $iterativePrompt = [
      ...$iterativePrompt,
      ...$agentResponse
    ];

    $agent->setOutputRules([
                             OutputRule::make([
                                                'name'       => 'draft_3',
                                                'rules'       => 'required|string',
                                                'description' => 'your updated draft based draft 1,2 and the user input.'
                                              ]),
                             OutputRule::make([
                                                'name'       => 'reflection_3',
                                                'rules'       => 'required|string',
                                                'description' => 'your reflection of the draft.'
                                              ])
                           ]);

    $agentResponse = $agent->handle($agentResponse);

    $iterativePrompt = [
      ...$iterativePrompt,
      ...$agentResponse
    ];

    $agent->setOutputRules([
                             OutputRule::make([
                                                'name'       => 'final_draft',
                                                'rules'       => 'required|string',
                                                'description' => 'your final_draft draft based on draft 1,2,3 and the user input.'
                                              ])
                           ]);

    $agentResponse = $agent->handle($agentResponse);

    $iterativePrompt = [
      ...$iterativePrompt,
      ...$agentResponse
    ];

  expect($iterativePrompt)->toBeArray()
                        ->and($agentResponse)->toHaveKeys(["draft_1", 'draft_2', 'draft_3', "final_draft"]);
});

