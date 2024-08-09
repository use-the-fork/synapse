<?php

  declare(strict_types=1);

  namespace UseTheFork\Synapse\OutputParsers;

use UseTheFork\Synapse\OutputParsers\Contracts\OutputParser;

abstract class BaseOutputParser implements OutputParser
{

  public string $expectedOutputFormat;

  public function invoke($input): mixed
	{
		return $this->parse($input);
	}

  protected function parse($input) {}
}
