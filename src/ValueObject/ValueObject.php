<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\ValueObject;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Throwable;
use UseTheFork\Synapse\ValueObject\Concerns\HandlesCallbacks;
use UseTheFork\Synapse\ValueObject\Contracts\Immutable;

/**
 * Base "ValueObject".
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @implements Arrayable<TKey, TValue>
 */
abstract class ValueObject implements Arrayable, Immutable
{
    use Conditionable;
    use HandlesCallbacks;
    use Macroable;

    /**
     * Get string representation of the value object.
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Get the internal property value.
     */
    public function __get(string $name): mixed
    {
        return $this->{$name};
    }

    /**
     * Make sure value object is immutable.
     *
     * @throws InvalidArgumentException
     */
    public function __set(string $name, mixed $value): void
    {
        throw new InvalidArgumentException(static::IMMUTABLE_MESSAGE);
    }

    /**
     * Get the object value.
     *
     * @return mixed
     */
    abstract public function value();

    /**
     * Convenient method to create a value object statically.
     */
    public static function make(mixed ...$values): static
    {
        return new static(...$values);
    }

    /**
     * Convenient method to create a value object statically.
     */
    public static function from(mixed ...$values): static
    {
        return static::make(...$values);
    }

    /**
     * Create a value object or return null.
     */
    public static function makeOrNull(mixed ...$values): ?static
    {
        try {
            return static::make(...$values);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Check if objects are instances of same class
     * and share the same properties and values.
     *
     * @param  ValueObject<int|string, mixed>  $object
     */
    public function equals(ValueObject $object): bool
    {
        return $this == $object;
    }

    /**
     * Inversion for `equals` method.
     *
     * @param  ValueObject<int|string, mixed>  $object
     */
    public function notEquals(ValueObject $object): bool
    {
        return ! $this->equals($object);
    }

    /**
     * Get an array representation of the value object.
     */
    public function toArray(): array
    {
        return (array) $this->value();
    }

    /**
     * Get string representation of the value object.
     */
    public function toString(): string
    {
        return (string) $this->value();
    }
}
