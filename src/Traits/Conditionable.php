<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits;

use UseTheFork\Synapse\Helpers\Helpers;

trait Conditionable
{
    /**
     * Invoke a callable when a given value returns a falsy value.
     *
     * @param  \Closure(): (mixed)|mixed  $value
     * @param  callable($this, mixed): (void)  $callback
     * @param  callable($this, mixed): (void)|null  $default
     * @return $this
     */
    public function unless(mixed $value, callable $callback, ?callable $default = null): static
    {
        $value = Helpers::value($value, $this);

        if (! $value) {
            $callback($this, $value);

            return $this;
        }

        if ($default) {
            $default($this, $value);
        }

        return $this;
    }

    /**
     * Invoke a callable where a given value returns a truthy value.
     *
     * @param  \Closure(): (mixed)|mixed  $value
     * @param  callable($this, mixed): (void)  $callback
     * @param  callable($this, mixed): (void)|null  $default
     * @return $this
     */
    public function when(mixed $value, callable $callback, ?callable $default = null): static
    {
        $value = Helpers::value($value, $this);

        if ($value) {
            $callback($this, $value);

            return $this;
        }

        if ($default) {
            $default($this, $value);
        }

        return $this;
    }
}
