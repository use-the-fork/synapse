<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

interface HasPromptGeneratedHook
{
    public function hookPromptGenerated(string $pendingAgentTask): string;
}
