<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AgentMemory extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }
}
