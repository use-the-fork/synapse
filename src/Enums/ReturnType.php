<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Enums;

    enum ReturnType: string
    {
        case STRING = 'string';
        case ARRAY = 'array';
        case OBJECT = 'object';
        case INT = 'int';
        case FLOAT = 'float';
        case BOOL = 'bool';
        case NULL = 'null';
        case RESOURCE = 'resource';
        case CALLABLE = 'callable';
        case ITERABLE = 'iterable';
    }
