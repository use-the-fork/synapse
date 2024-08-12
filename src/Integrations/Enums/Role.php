<?php

  declare(strict_types=1);

  namespace UseTheFork\Synapse\Integrations\Enums;

  enum Role: string
  {
    case SYSTEM = 'system';
    case USER = 'user';
    case AGENT = 'agent';
  }
