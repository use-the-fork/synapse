<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

interface HasPromptParsedHook
{
    public function hookPromptParsed(array $pendingAgentTask): array;
}
