<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse;

    use UseTheFork\Synapse\AgentTask\PendingAgentChain;
    use UseTheFork\Synapse\Traits\HasConfig;
    use UseTheFork\Synapse\Traits\Makeable;
    use UseTheFork\Synapse\ValueObject\Message;

    class AgentChain
    {
        use Makeable;
        use HasConfig;

        protected PendingAgentChain $pendingAgentChain;
        protected                   $pipeline;

        public function __construct(array $agents)
        {
            foreach ($agents as $agent) {
                if(! ($agent instanceof Agent)){
                    throw new \Exception("Agent must be an instance of Agent");
                }
            }

            $this->config()->add('persistInputs', []);
            $this->config()->add('agents', collect($agents));
            $this->pendingAgentChain = $this->createPendingAgentChain();

        }

        /**
         * Create a new PendingAgentTask
         */
        public function createPendingAgentChain(): PendingAgentChain
        {
            return new PendingAgentChain($this);
        }

        /**
         * Handles the user input and extra agent arguments to retrieve the response.
         *
         * @param  array|null  $input  The input array.
         * @param  array|null  $extraAgentArgs  The extra agent arguments array.
         * @return Message The final message from the agent.
         *
         * @throws Throwable
         */
        public function handle(?array $input): Message
        {
            $this->config()->add('input', $input);
            $this->config()->get('agents')->each(function ($agent) use ($input) {
                $input = [
                    ...$this->config()->get('input'),
                    ...$this->config()->get('persistInputs')
                ];
                $response = $agent->handle($input);
                $this->config()->add('input', $response->content());
            });

            return Message::make([
                'role' => 'agent',
                'finish_reason' => 'stop',
                'content' => $this->config()->get('input')
                                 ]);
        }

        public function persistInputs(array $inputs): static
        {
            $this->config()->add('persistInputs', $inputs);

            return $this;
        }
    }
