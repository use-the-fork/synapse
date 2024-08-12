<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Integrations\ValueObjects;

use UseTheFork\Synapse\ValueObject\ArrayValueObject;

class MessageValueObject extends ArrayValueObject
{
    /**
     * Define the rules for email validator.
     */
    protected function validationRules(): array
    {
        return [
            'role' => 'required',
            'finish_reason' => 'nullable|sometimes|string',
            'content' => 'nullable|sometimes|string',
            'tool_call_id' => 'nullable|sometimes|string',
            'tool_name' => 'nullable|sometimes|string',
            'tool_arguments' => 'nullable|sometimes|string',
            'tool_calls' => 'nullable|sometimes',
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

    public function finishReason(): string
    {
        return $this->value['finish_reason'];
    }

    public function content(): ?string
    {
        return ! empty($this->value['content']) ? $this->value['content'] : null;
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
