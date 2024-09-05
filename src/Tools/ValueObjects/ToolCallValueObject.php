<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Tools\ValueObjects;

use UseTheFork\Synapse\ValueObject\ArrayValueObject;

class ToolCallValueObject extends ArrayValueObject
{
    /**
     * Define the rules for email validator.
     */
    protected function validationRules(): array
    {
        return [
            'id' => 'required|string',
            'type' => 'required|string',
            'function' => 'required|array',
            'function.name' => 'required|string',
            'function.arguments' => 'required|json',
        ];
    }

    /**
     * Apply sanitization rules
     */
    protected function sanitize(): void {}

    public function content(): string
    {
        return $this->value['content'];
    }

    public function role(): string
    {
        return $this->value['role'];
    }

    public function toolCalls(): array
    {
        return $this->value['tool_calls'];
    }
}
