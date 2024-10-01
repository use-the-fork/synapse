<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Helpers;

    use Saloon\Enums\PipeOrder;
    use UseTheFork\Synapse\Agents\Agent;
    use UseTheFork\Synapse\Agents\PendingAgentTask;

    class MiddlewarePipeline
    {
        /**
         * Boot Task Pipeline
         */
        protected Pipeline $bootTaskPipeline;

        protected Pipeline $startIterationPipeline;

        protected Pipeline $startThreadPipeline;

        /**
         * Start Task Pipeline
         */
        protected Pipeline $startTaskPipeline;

        /**
         * Complete Task Pipeline
         */
        protected Pipeline $completeTaskPipeline;


        /**
         * Constructor
         */
        public function __construct()
        {
            $this->startThreadPipeline = new Pipeline;
        }

        public function onStartThread(callable $callable, ?string $name = null, ?PipeOrder $order = null): static
        {
            $this->startIterationPipeline->pipe(static function (PendingAgentTask $pendingAgentTask) use ($callable): PendingAgentTask {
                $result = $callable($pendingAgentTask);

                if ($result instanceof PendingAgentTask) {
                    return $result;
                }

                return $pendingAgentTask;
            }, $name, $order);

            return $this;
        }

        /**
         * Process the request pipeline.
         */
        public function executeStartTaskPipeline(PendingAgentTask $pendingAgent): PendingAgentTask
        {
            return $this->startTaskPipeline->process($pendingAgent);
        }


        public function getStartIterationPipeline(): Pipeline
        {
            return $this->startIterationPipeline;
        }

        /**
         * Merge in another middleware pipeline.
         *
         * @return $this
         */
        public function merge(MiddlewarePipeline $middlewarePipeline): static
        {
            $startIterationPipes = array_merge(
                $this->getStartIterationPipeline()->getPipes(),
                $middlewarePipeline->getStartIterationPipeline()->getPipes()
            );


            $this->startIterationPipeline->setPipes($startIterationPipes);

            return $this;
        }

    }
