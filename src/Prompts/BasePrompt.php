<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Prompts;

use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\OutputParsers\Contracts\OutputParser;
use UseTheFork\Synapse\Prompts\Contracts\Prompt;

abstract class BasePrompt implements Prompt
{
    protected string $bladePrompt;
    protected string $expectedOutputFormat;
    protected array $extraInputs = [];

    public function setOutputFormat(string $format): void
    {
        $this->expectedOutputFormat = $format;
    }

    public function get(array $inputs, OutputParser $outputParser, Memory $memory, array $tools = []): string
    {
      $toolNames = [];
      foreach ($tools as $name => $tool) {
        $toolNames[] = $name;
      }

      return view($this->bladePrompt, [
        ...$inputs,
        ...$this->extraInputs,
        'expectedOutputFormat' => $outputParser->getOutputFormat(),
        'memory' => $memory->asString(),
        'tools' => $toolNames
      ])->render();
    }
}
