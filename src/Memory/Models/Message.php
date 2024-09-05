<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Memory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    ];

    public function assistant()
    {
        return $this->belongsTo(AgentMemory::class);
    }
}
