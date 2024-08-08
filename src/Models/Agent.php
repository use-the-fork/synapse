<?php

declare(strict_types=1);
//Credits to https://github.com/bootstrapguru/dexor

namespace UseTheFork\Synapse\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
        'model',
        'description',
        'prompt',
        'tools',
        'service',
    ];

    protected $casts = [
        'tools' => 'array',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
