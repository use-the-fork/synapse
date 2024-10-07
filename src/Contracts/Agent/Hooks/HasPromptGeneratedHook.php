<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent\Hooks;

interface HasPromptGeneratedHook
{
    /**
     * This hook is invoked after a prompt is generated and before it's parsed in to messages.
     *
     * @param  string  $generatedPrompt  The generated prompt string.
     * @return string Modified or processed prompt string.
     */
    public function hookPromptGenerated(string $generatedPrompt): string;
}
