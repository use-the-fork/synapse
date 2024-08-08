<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Models\Agents;

use UseTheFork\Synapse\Models\Agent;

class SimpleAgent extends Agent
{
    protected $table = 'agents';

    protected $primaryKey = 'agent_id';

    protected $attributes = [
        'prompt' => 'You Are a helpful assistant',
        'delayed' => false,
    ];

    public function invoke($query)
    {
        $this->messages()->create([
            'role' => 'user',
            'content' => $query,
        ]);

        dd(
            $this->messages()
        );
    }
}
