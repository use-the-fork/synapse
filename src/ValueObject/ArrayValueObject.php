<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\ValueObject;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class ArrayValueObject extends ValueObject
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
        if ($this->value() === []) {
            throw new InvalidArgumentException('Request Payload cannot be empty.');
        }

        $validator = Validator::make(
            $this->value(),
            $this->validationRules(),
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->messages());
        }

        //Set the validated values as the new value to remove any noise
        $this->value = $validator->validated();
    }

    /**
     * Define the rules for email validator.
     */
    protected function validationRules(): array
    {
        return [];
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

    public function value(): array
    {
        return $this->toArray();
    }
}
