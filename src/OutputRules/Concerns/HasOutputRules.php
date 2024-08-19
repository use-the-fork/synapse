<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\OutputRules\Concerns;

use Illuminate\Support\Facades\Validator;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\OutputRules\ValueObjects\OutputRule;

/**
 * Trait HasOutputParser
 */
trait HasOutputRules
{
    protected bool $hasOutputRules = true;

    protected array $outputRules = [];

    /**
     * returns the memory type this Agent should use.
     *
     * @return []
     */
    protected function registerOutputRules(): array
    {
        return [];
    }

    /**
     * sets the initial output rules type this agent will use.
     */
    protected function initializeOutputRules(): void
    {
        $this->outputRules = $this->registerOutputRules();
    }

    public function setOutputRules(array $rules = []): void
    {
        $this->outputRules = $rules;
    }

    public function addOutputRule(OutputRule $rule): void
    {
        $this->outputRules[] = $rule;
    }

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

    protected function doValidate(string $response)
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

    protected function parseResponse($input)
    {
        return json_decode(
            str($input)->replace([
                '```json',
                '```',
            ], '')->toString(), true
        );
    }

    protected function doRevalidate(string $result, string $errors = '')
    {
        $prompt = Message::make([
            'role' => 'user',
            'content' => "### Instruction\nRewrite user-generated content to adhere to the specified format.\n\n{$this->getOutputRules()}\n\n{$errors}### User Content\n{$result}",
        ]);

        return $this->integration->handle(
            [$prompt],
            []
        );
    }
}
