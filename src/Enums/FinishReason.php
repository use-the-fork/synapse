<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Enums;

    enum FinishReason: string
    {
        case TOOL_CALL = 'tool_calls';
        case STOP = 'stop';
    }
