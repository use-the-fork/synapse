<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'assistant_id',
        'role',
        'content',
        'tool_name',
        'tool_arguments',
        'tool_call_id',
        'tool_content',
        'image',
    ];

    protected $casts = [
        'image' => 'array',
    ];

    public function assistant()
    {
        return $this->belongsTo(AgentMemory::class);
    }
}
