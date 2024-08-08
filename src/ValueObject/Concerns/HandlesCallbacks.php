<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\ValueObject\Concerns;

use Closure;

trait HandlesCallbacks
{
    /**
     * Callback to hook into parent construct
     * before any other call is performed.
     */
    protected Closure $before;

    /**
     * Set the "before" callback.
     */
    protected function beforeParentCalls(Closure $callback): void
    {
        $this->before = $callback;
    }
}
