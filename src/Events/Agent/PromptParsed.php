<?php

namespace UseTheFork\Synapse\Events\Agent;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use UseTheFork\Synapse\ValueObject\Message;

class PromptParsed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  array<Message>  $parsedPrompt  The parsed prompt array.
     */
    public function __construct(
        public array $parsedPrompt,
    ) {}
}
