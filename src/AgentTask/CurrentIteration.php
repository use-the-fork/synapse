<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\AgentTask;


use UseTheFork\Synapse\Enums\FinishReason;
use UseTheFork\Synapse\ValueObject\Message;

class CurrentIteration
{
    protected array $extraAgentArgs = [];

    protected array $promptChain = [];

    protected ?Message $agentResponseMessage = null;

    protected int $iterationCount = 1;

    public function finishReason(): FinishReason
	{
		return FinishReason::from($this->agentResponseMessage->finishReason());
	}

    public function getResponse(): Message|null
	{
		return $this->agentResponseMessage;
	}

    public function setResponse(Message $agentResponseMessage): void
	{
		$this->agentResponseMessage = $agentResponseMessage;
	}

    public function setPromptChain(array $prompt): void
	{
		$this->promptChain = $prompt;
	}

    public function getPromptChain(): array
	{
		return $this->promptChain;
	}

    public function setExtraAgentArgs(array $extraAgentArgs): void
	{
		$this->extraAgentArgs = $extraAgentArgs;
	}

    public function getExtraAgentArgs(): array
	{
		return $this->extraAgentArgs;
	}

    public function getIterationCount(): int
	{
		return $this->iterationCount;
	}

    public function incrementIterationCount(): void
	{
		$this->iterationCount++;
	}
}
