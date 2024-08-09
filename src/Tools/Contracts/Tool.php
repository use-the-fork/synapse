<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tools\Contracts;


use UseTheFork\Synapse\Integrations\Exceptions\InvalidEnvironmentException;

interface Tool
{
  /**
   * Implement Environment validation.
   *
   * @throws InvalidEnvironmentException
   */
  public function validateEnvironment(): void;

}
