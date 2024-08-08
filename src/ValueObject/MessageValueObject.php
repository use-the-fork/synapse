<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\ValueObject;

use Illuminate\Support\Facades\Validator;

class MessageValueObject extends ArrayValueObject
{
    /**
     * Define the rules for email validator.
     */
    protected function validationRules(): array
    {
        return [
            'role' => 'required',
            'content' => 'nullable|sometimes|string',
            'tool_call_id' => 'nullable|sometimes|string',
            'tool_name' => 'nullable|sometimes|string',
            'tool_calls' => 'nullable|sometimes|array',
        ];
    }

    /**
     * Apply sanitization rules
     */
    protected function sanitize(): void
    {

        if (empty($this->value['tool_calls'])) {
            $this->value['tool_calls'] = [];
        }
    }

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
