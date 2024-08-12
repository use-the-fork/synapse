<?php

  declare(strict_types=1);

  namespace UseTheFork\Synapse\Integrations\Enums;

  enum ResponseType: string
  {
    case TOOL_CALL = 'tool_calls';
    case STOP = 'stop';
  }
