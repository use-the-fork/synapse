<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Contracts\Agent;

use UseTheFork\Synapse\Contracts\Memory;

interface HasMemory
{
	public function resolveMemory(): Memory;
}
