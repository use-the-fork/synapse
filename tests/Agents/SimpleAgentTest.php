<?php

declare(strict_types=1);

use UseTheFork\Synapse\Agents\SimpleAgent;

it('connects to OpenAI', function () {
    $simpleAgent = new SimpleAgent();
    $t = $simpleAgent->invoke('hi, how are you?');

    dd($t);
});
