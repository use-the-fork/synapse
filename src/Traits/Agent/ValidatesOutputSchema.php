<?php

    declare(strict_types=1);

    namespace UseTheFork\Synapse\Traits\Agent;

    use Illuminate\Support\Facades\Validator;
    use Throwable;
    use UseTheFork\Synapse\AgentTask\PendingAgentTask;
    use UseTheFork\Synapse\Enums\PipeOrder;
    use UseTheFork\Synapse\Exceptions\MaximumIterationsException;
    use UseTheFork\Synapse\Exceptions\MissingResolverException;
    use UseTheFork\Synapse\Traits\HasMiddleware;
    use UseTheFork\Synapse\ValueObject\Message;
    use UseTheFork\Synapse\ValueObject\SchemaRule;

    /**
     * Indicates if the agent has an output schema.
     */
    trait ValidatesOutputSchema
    {
        use HasMiddleware;

        /**
         * The maximum number "loops" that this Validation should run .
         */
        protected int $maximumValidationIterations = 5;

        /**
         * sets the initial output schema type this agent will use.
         */
        public function bootValidatesOutputSchema(): void
        {
            $this->middleware()->onStartThread(fn(PendingAgentTask $pendingAgentTask) => $this->addOutputSchema($pendingAgentTask), 'addOutputSchema');
            $this->middleware()->onEndThread(fn(PendingAgentTask $pendingAgentTask) => $this->doValidateOutputSchema($pendingAgentTask), 'doValidateOutputSchema', PipeOrder::LAST);
        }

        /**
         * @throws MissingResolverException
         */
        public function addOutputSchema(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {
            $pendingAgentTask->addInput('outputSchema', $this->getOutputSchema());
            return $pendingAgentTask;
        }

        /**
         * Retrieves the output rules as a JSON string.
         *
         * @return string|null The output rules encoded as a JSON string. Returns null if there are no output rules.
         * @throws MissingResolverException
         */
        public function getOutputSchema(): ?string
        {
            $outputParserPromptPart = [];
            foreach ($this->resolveOutputSchema() as $rule) {
                $outputParserPromptPart[$rule->getName()] = "({$rule->getRules()}) {$rule->getDescription()}";
            }

            return "```json\n" . json_encode($outputParserPromptPart, JSON_PRETTY_PRINT) . "\n```";
        }

        /**
         * Sets the output rules for validation.
         *
         * @return array<SchemaRule>
         * @throws MissingResolverException
         */
        public function resolveOutputSchema(): array
        {
            throw new MissingResolverException('ValidatesOutputSchema', 'resolveOutputSchema');
        }

        /**
         * Performs validation on the given response.
         *
         * @param PendingAgentTask $pendingAgentTask The response to validate.
         *
         * @return PendingAgentTask If validation passes, it returns the validated response. Otherwise, it enters a loop and performs revalidation.
         *
         * @throws Throwable
         */
        public function doValidateOutputSchema(PendingAgentTask $pendingAgentTask): PendingAgentTask
        {

            $response = $pendingAgentTask->currentIteration()->getResponse()->content();

            $outputSchema = [];
            collect($this->resolveOutputSchema())->each(function ($rule) use (&$outputSchema): void {
                $outputSchema[$rule->getName()] = $rule->getRules();
            });

            for ($i = 1; $i <= $this->maximumValidationIterations; $i++) {
                $result = $this->parseResponse($response);
                $errorsAsString = '';
                if (!empty($result)) {
                    $validator = Validator::make($result, $outputSchema);
                    if (!$validator->fails()) {

                        $currentResponse = $pendingAgentTask->currentIteration()->getResponse();
                        $updatedResponse = Message::make([
                                                             ...$currentResponse->toArray(),
                                                             'content' => $validator->validated(),
                                                         ]);
                        $pendingAgentTask->currentIteration()->setResponse($updatedResponse);

                        return $pendingAgentTask;
                    }

                    $errorsFlat = collect();
                    $errors = $validator->errors()->messages();
                    foreach ($errors as $error) {
                        $errorsFlat->push(implode(PHP_EOL, $error));
                    }
                    $errorsFlat = $errorsFlat->implode(PHP_EOL);
                    $errorsAsString = $errorsFlat . "\n\n";
                }
                $response = $this->doRevalidate($response, $errorsAsString);

                //since all integrations return a Message value object we need to grab the content
                $response = $response->currentIteration()->getResponse()->content();
            }

            throw new MaximumIterationsException($this->maximumValidationIterations);
        }

        /**
         * Parses the input response and returns it as an associative array.
         *
         * @param string $input The input response to parse.
         *
         * @return array|null The parsed response as an associative array, or null if parsing fails.
         */
        protected function parseResponse(string $input): ?array
        {

            # we attempt to Decode the Json in a few ways. It's best to give all models a chance before failing.
            $jsonFormat =  json_decode(
                str($input)->replace([
                                         '```json',
                                         '```',
                                     ], '')->toString(), TRUE
            );

            if(!empty($jsonFormat)){
                return $jsonFormat;
            }

            return json_decode(
                str($input)->replace([
                                         '```',
                                         '```',
                                     ], '')->toString(), TRUE
            );
        }

        /**
         * Performs revalidation on the given result.
         *
         * @param string $errors The validation errors.
         *
         * @return PendingAgentTask The result of handling the validation completion against the prompt chain.
         *
         * @throws Throwable
         */
        protected function doRevalidate(string $response, string $errors = ''): PendingAgentTask
        {

            $prompt = view('synapse::Prompts.ReValidateResponsePrompt', [
                'outputSchema' => $this->getOutputSchema(),
                'lastResponse' => $response,
                'errors'      => $errors
            ])->render();

            $validationPrompt = Message::make([
                                        'role'    => 'user',
                                        'content' => $prompt,
                                    ]);


            //We get the whole conversation so far but append a validation message
            $promptChain = $this->pendingAgentTask->currentIteration()->getPromptChain();

            $this->pendingAgentTask->currentIteration()->setPromptChain([
                ...$promptChain,
                $validationPrompt
                                                                        ]);

            // Create the Chat request we will be sending.
            return $this->integration->handlePendingAgentTaskCompletion($this->pendingAgentTask);
        }
    }
