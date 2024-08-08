<?php
  #Credits to https://github.com/bootstrapguru/dexor
  namespace UseTheFork\Synapse\Data;

  use Spatie\LaravelData\Data;

  class ToolCallData extends Data
  {
    public function __construct(
      public string $id,
      public string $type,
      public ToolFunctionData $function,
    ) {}
  }
