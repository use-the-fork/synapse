<?php

namespace UseTheFork\Synapse\Exceptions;


class MaximumIterationsException extends SynapseException {

    /**
     * Constructor
     */
    public function __construct(int $number)
    {
        parent::__construct(sprintf('The Agent iterated more then the allowed total of %s', $number));
    }

}
