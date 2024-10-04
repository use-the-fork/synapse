<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Exceptions;

    class MissingResolverException extends SynapseException
    {
        /**
         * Constructor
         */
        public function __construct(string $trait, string $method)
        {
            parent::__construct(sprintf('The "%s" trait requires a "%s" method.', $trait, $method));
        }
    }
