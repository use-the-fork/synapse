<?php

declare(strict_types=1);

use UseTheFork\Synapse\Agents\SimpleAgent;
use UseTheFork\Synapse\Tools\SearchGoogleTool;

it('connects to OpenAI', function () {
    $simpleAgent = new SimpleAgent([SearchGoogleTool::class]);
    $t = $simpleAgent->invoke('search google for the current president of the united states.');

    dd($t);
});
