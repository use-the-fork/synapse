<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse;

    use UseTheFork\Synapse\ValueObject\Message;

    abstract class AgentChain
    {

        /**
         * Handles the user input and extra agent arguments to retrieve the response.
         *
         * @param  array|null  $input  The input array.
         * @param  array|null  $extraAgentArgs  The extra agent arguments array.
         * @return Message The final message from the agent.
         *
         * @throws Throwable
         */
        abstract public function handle(?array $input, ?array $extraAgentArgs): Message;
    }
