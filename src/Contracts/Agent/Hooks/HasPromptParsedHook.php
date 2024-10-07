<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

use UseTheFork\Synapse\ValueObject\Message;

interface HasPromptParsedHook
{
    /**
     * Hook to be triggered after a prompt is parsed.
     *
     * @param array<Message> $parsedPrompt The parsed prompt data.
     *
     * @return array<Message> The modified prompt data.
     */
    public function hookPromptParsed(array $parsedPrompt): array;
}
