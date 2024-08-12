<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\OutputRules\ValueObjects;

use InvalidArgumentException;
use UseTheFork\Synapse\ValueObject\ValueObject;

class OutputRule extends ValueObject
{
    protected array $value;

    public function __construct(array $value)
    {

        if (isset($this->value)) {
            throw new InvalidArgumentException(static::IMMUTABLE_MESSAGE);
        }

        $this->value = $value;

        if (isset($this->before)) {
            ($this->before)();
        }

        $this->validate();
        $this->sanitize();
    }

    protected function validate(): void
    {
        if (empty($this->value())) {
            throw new InvalidArgumentException('a output rule cannot be empty.');
        }

        if (
          empty($this->value['name'])
        ) {
          throw new InvalidArgumentException('a name is required.');
        }

        if (
          empty($this->value['rules'])
        ) {
          throw new InvalidArgumentException('rules are required.');
        }

        if (
          empty($this->value['description'])
        ) {
          throw new InvalidArgumentException('a description is required.');
        }

    }

    /**
     * Apply sanitization rules
     */
    protected function sanitize(): void {}

    /**
     * Get an array representation of the value object.
     */
    public function toArray(): array
    {
        return $this->value;
    }

    public function value()
    {
        return $this->toArray();
    }

    public function getName() : string
    {
        return $this->value['name'];
    }
    public function getDescription() : string
    {
        return $this->value['description'];
    }
    public function getRules() : string
    {
        return $this->value['rules'];
    }


}
