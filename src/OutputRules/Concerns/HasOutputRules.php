<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\OutputRules\Concerns;

use Illuminate\Support\Facades\Validator;
use Throwable;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

/**
 * Indicates if the application has output rules.
 */
trait HasOutputRules
{
    protected bool $hasOutputRules = true;

    protected array $outputRules = [];

    /**
     * Adds an output rule to the application.
     *
     * @param  OutputRule  $rule  The output rule to be added.
     */
    public function addOutputRule(OutputRule $rule): void
    {
        $this->outputRules[] = $rule;
    }

    /**
     * Performs validation on the given response.
     *
     * @param  string  $response  The response to validate.
     * @return mixed If validation passes, it returns the validated response. Otherwise, it enters a loop and performs revalidation.
     *
     * @throws Throwable
     */
    protected function doValidate(string $response): mixed
    {

        if (! $this->hasOutputRules) {
            return $response;
        }

        $outputRules = [];
        collect($this->outputRules)->each(function ($rule) use (&$outputRules) {
            $outputRules[$rule->getName()] = $rule->getRules();
        });

        while (true) {
            $result = $this->parseResponse($response);
            $errorsAsString = '';
            if (! empty($result)) {
                $validator = Validator::make($result, $outputRules);
                if (! $validator->fails()) {
                    return $validator->validated();
                }

                $errors = $validator->errors()->toArray();
                $errorsFlat = array_reduce($errors, function ($carry, $item) {
                    return array_merge($carry, is_array($item) ? $item : [$item]);
                }, []);
                $errorsAsString = "### Here are the errors that Failed validation \n".implode("\n", $errorsFlat)."\n\n";
            }
            $response = $this->doRevalidate($response, $errorsAsString);
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
     * @return mixed The result of handling the validation completion.
     *
     * @throws Throwable
     */
    protected function doRevalidate(string $result, string $errors = ''): mixed
    {

        $prompt = view('synapse::Prompts.ReValidateResponsePrompt', [
            'outputRules' => $this->getOutputRules(),
            'errors' => $errors,
            'result' => $result,
        ])->render();

        $prompt = Message::make([
            'role' => 'user',
            'content' => $prompt,
        ]);

        return $this->integration->handleValidationCompletion($prompt);
    }

    /**
     * Retrieves the output rules as a JSON string.
     *
     * @return string|null The output rules encoded as a JSON string. Returns null if there are no output rules.
     */
    public function getOutputRules(): ?string
    {
        if (! $this->hasOutputRules) {
            return null;
        }

        $outputParserPromptPart = [];
        foreach ($this->outputRules as $rule) {
            $outputParserPromptPart[$rule->getName()] = "({$rule->getRules()}) {$rule->getDescription()}";
        }

        return "```json\n".json_encode($outputParserPromptPart, JSON_PRETTY_PRINT)."\n```";
    }

    /**
     * Sets the output rules for validation.
     *
     * @param  array  $rules  The output rules to be set.
     */
    public function setOutputRules(array $rules = []): void
    {
        $this->outputRules = $rules;
    }

    /**
     * sets the initial output rules type this agent will use.
     */
    protected function initializeOutputRules(): void
    {
        $this->outputRules = $this->registerOutputRules();
    }

    /**
     * returns the memory type this Agent should use.
     */
    protected function registerOutputRules(): array
    {
        return [];
    }
}
