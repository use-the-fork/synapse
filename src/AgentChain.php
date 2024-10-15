<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use UseTheFork\Synapse\Traits\Makeable;

class AgentChain
{
    use Makeable;

    protected $pipeline;

    public function __construct(array $agents)
    {
        foreach ($agents as $agent) {

            $agent = new $agent;

            dd($agent);

        }
    }

    public function handle(?array $input, ?array $extraAgentArgs = []): static
    {

        return $this;
    }
}
