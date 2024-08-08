<?php
  #Credits to https://github.com/bootstrapguru/dexor
  namespace UseTheFork\Synapse\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

  class Assistant extends Model
  {
    use HasFactory;

    protected $fillable = [
      'name',
      'model',
      'description',
      'prompt',
      'tools',
      'service'
    ];

    protected $casts = [
      'tools' => 'array',
    ];

    public function threads()
    {
      return $this->hasMany(Thread::class);
    }
  }
