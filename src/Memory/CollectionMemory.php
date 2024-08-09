<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Memory;

use Illuminate\Support\Collection;
use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\ValueObject\MessageValueObject;

class CollectionMemory implements Memory
{

  protected Collection $agentMemory;

  public function __construct() {
    $this->agentMemory = collect();
  }

  public function create(MessageValueObject $message): void
  {
    $this->agentMemory->push($message->toArray());
  }

  public function get(): array
  {
    return $this->agentMemory->toArray();
  }

  public function load(): void
  {}
}
