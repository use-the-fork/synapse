<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Traits\Agent;

use Illuminate\Support\Facades\Validator;
use Throwable;
use UseTheFork\Synapse\AgentTask\PendingAgentTask;
use UseTheFork\Synapse\Enums\PipeOrder;
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
     * Performs validation on the given response.
     *
     * @param  PendingAgentTask  $pendingAgentTask  The response to validate.
     * @return PendingAgentTask If validation passes, it returns the validated response. Otherwise, it enters a loop and performs revalidation.
     *
     * @throws Throwable
     */
    protected function doValidateOutputSchema(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {

        $response = $pendingAgentTask->currentIteration()->getResponse()->content();

        $outputSchema = [];
        collect($this->defaultOutputSchema())->each(function ($rule) use (&$outputSchema): void {
            $outputSchema[$rule->getName()] = $rule->getRules();
        });

        while (true) {
            $result = $this->parseResponse($response);
            $errorsAsString = '';
            if (! empty($result)) {
                $validator = Validator::make($result, $outputSchema);
                if (! $validator->fails()) {

                    $currentResponse = $pendingAgentTask->currentIteration()->getResponse();
                    $updatedResponse = Message::make([
                        ...$currentResponse->toArray(),
                        'content' => $validator->validated(),
                    ]);
                    $pendingAgentTask->currentIteration()->setResponse($updatedResponse);

                    return $pendingAgentTask;
                }

                $errors = $validator->errors()->toArray();
                $errorsFlat = array_reduce($errors, function ($carry, $item): array {
                    return array_merge($carry, is_array($item) ? $item : [$item]);
                }, []);
                $errorsAsString = "### Here are the errors that Failed validation \n".implode("\n", $errorsFlat)."\n\n";
            }
            $response = $this->doRevalidate($response, $pendingAgentTask, $errorsAsString);

            //since all integrations return a Message value object we need to grab the content
            $response = $response->content();
        }
    }

    /**
     * Parses the input response and returns it as an associative array.
     *
     * @param  string  $input  The input response to parse.
     * @return array|null The parsed response as an associative array, or null if parsing fails.
     */
    protected function parseResponse(string $input): ?array
    {
        return json_decode(
            str($input)->replace([
                '```json',
                '```',
            ], '')->toString(), true
        );
    }

    /**
     * Performs revalidation on the given result.
     *
     * @param  string  $result  The result to revalidate.
     * @param  string  $errors  The validation errors.
     * @return Message The result of handling the validation completion.
     *
     * @throws Throwable
     */
    protected function doRevalidate(string $result, PendingAgentTask $pendingAgentTask, string $errors = ''): Message
    {

        $agent = $pendingAgentTask->getAgent();

        $prompt = view('synapse::Prompts.ReValidateResponsePrompt', [
            'outputRules' => $this->getOutputSchema(),
            'errors' => $errors,
            'result' => $result,
        ])->render();

        $prompt = Message::make([
            'role' => 'user',
            'content' => $prompt,
        ]);

        return $agent->integration()->handleCompletion($prompt);
    }

    /**
     * Retrieves the output rules as a JSON string.
     *
     * @return string|null The output rules encoded as a JSON string. Returns null if there are no output rules.
     */
    public function getOutputSchema(): ?string
    {
        $outputParserPromptPart = [];
        foreach ($this->defaultOutputSchema() as $rule) {
            $outputParserPromptPart[$rule->getName()] = "({$rule->getRules()}) {$rule->getDescription()}";
        }

        return "```json\n".json_encode($outputParserPromptPart, JSON_PRETTY_PRINT)."\n```";
    }

    public function addOutputSchema(PendingAgentTask $pendingAgentTask): PendingAgentTask
    {
        $pendingAgentTask->addInput('outputSchema', $this->getOutputSchema());

        return $pendingAgentTask;
    }

    /**
     * sets the initial output schema type this agent will use.
     */
    public function bootValidatesOutputSchema(PendingAgentTask $pendingAgentTask): void
    {
        $this->middleware()->onStartThread(fn (PendingAgentTask $pendingAgentTask) => $this->addOutputSchema($pendingAgentTask), 'addOutputSchema');
        $this->middleware()->onEndThread(fn (PendingAgentTask $pendingAgentTask) => $this->doValidateOutputSchema($pendingAgentTask), 'doValidateOutputSchema', PipeOrder::LAST);
    }

    /**
     * Sets the output rules for validation.
     *
     * @param  array<SchemaRule>  $rules  The output rules to be set.
     */
    protected function defaultOutputSchema(): array
    {
        return [];
    }
}
