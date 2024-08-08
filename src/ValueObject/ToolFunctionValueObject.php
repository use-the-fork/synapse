<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\ValueObject;

class ToolFunctionValueObject extends ArrayValueObject
{
    /**
     * Define the rules for email validator.
     */
    protected function validationRules(): array
    {
        return [
            'name' => 'required|string',
            'arguments' => 'required|json',
        ];
    }

    /**
     * Apply sanitization rules
     */
    protected function sanitize(): void
    {
        $this->value['arguments'] = json_decode($this->value['arguments'], true);
    }
}
