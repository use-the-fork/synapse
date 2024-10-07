<?php

namespace UseTheFork\Synapse\Events\Agent;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PromptGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $generatedPrompt,
    ) {}
}
