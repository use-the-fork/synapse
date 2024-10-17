<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\AgentTask;

use UseTheFork\Synapse\AgentChain;
use UseTheFork\Synapse\AgentTask\StartTasks\BootTraits;

class PendingAgentChain
{

	protected AgentChain $agentChain;

	public function __construct(AgentChain $agentChain)
	{
		$this->agentChain = $agentChain;

		$this
			->tap(new BootTraits);

	}

	/**
	 * Tap into the agent chain
	 *
	 * @return $this
	 */
	protected function tap(callable $callable): static
	{
		$callable($this);

		return $this;
	}

    /**
     * Retrieve the agent associated with the current task.
     *
     * @return AgentChain The current agent instance.
     */
    public function agent(): AgentChain
    {
        return $this->agentChain;
    }

}
