<?php

  declare(strict_types=1);

  namespace UseTheFork\Synapse\OutputParsers;

use UseTheFork\Synapse\OutputParsers\Contracts\OutputParser;

class StringOutputParser extends BaseOutputParser implements OutputParser
{
  protected function parse($input)
	{
		return str($input)->toString();
	}
}
