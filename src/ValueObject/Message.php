<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\ValueObject;

class Message extends ArrayValueObject
{
    public function content(): mixed
    {
        return empty($this->value['content']) ? null : $this->value['content'];
    }

    public function finishReason(): string
    {
        return $this->value['finish_reason'];
    }

    public function role(): string
    {
        return $this->value['role'];
    }

    public function toolArguments(): string|null
    {
        return $this->value['tool_arguments'] ?? null;
    }

    public function toolContent(): string|null
    {
        return $this->value['tool_content'] ?? null;
    }

    public function toolName(): string|null
    {
        return $this->value['tool_name'] ?? null;
    }

    /**
     * Apply sanitization rules
     */
    protected function sanitize(): void {}

    /**
     * Define the rules for Message validator.
     */
    protected function validationRules(): array
    {
        return [
            'role' => 'required',
            'finish_reason' => 'nullable|sometimes|string',
            'content' => 'nullable|sometimes',

            'tool_call_id' => 'nullable|sometimes|string',
            'tool_name' => 'nullable|sometimes|string',
            'tool_arguments' => 'nullable|sometimes|string',
            'tool_content' => 'nullable|sometimes',

            'image' => 'nullable|sometimes|array',
            'image.url' => 'nullable|sometimes|string',
        ];
    }
}
