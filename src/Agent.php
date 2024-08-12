<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Exception;
use UseTheFork\Synapse\Integrations\Enums\ResponseType;
use UseTheFork\Synapse\Integrations\ValueObjects\MessageValueObject;
use UseTheFork\Synapse\OutputRules\Concerns\HasOutputRules;

class Agent
{
    use Concerns\HasEvents,
        HasOutputRules,
        Integrations\Concerns\HasIntegration,
        Memory\Concerns\HasMemory,
        OutputRules\Concerns\HasOutputRules,
        Tools\Concerns\HasTools;

    /**
     * The view to use when generating the prompt for this agent
     */
    protected string $promptView;

    /**
     * a keyed array of values to be used as extra inputs that are passed to the prompt when it is generated.
     */
    protected array $extraInputs = [];

    /**
     * The array of booted agents.
     */
    protected static array $booted = [];

    public function __construct(array $attributes = [])
    {
        $this->fireModelEvent('booting', false);
        $this->initializeAgent();
        $this->fireModelEvent('booted', false);
    }

    protected function initializeAgent(): void
    {
        $this->fireModelEvent('booting', false);

        $this->initializeIntegration();
        $this->initializeMemory();
        $this->initializeTools();
        $this->initializeOutputRules();

        $this->fireModelEvent('booted', false);
    }

    public function getPrompt(array $inputs): string
    {
        $toolNames = [];
        foreach ($this->tools as $name => $tool) {
            $toolNames[] = $name;
        }

        return view($this->promptView, [
            ...$inputs,
            ...$this->extraInputs,
            'outputRules' => $this->getOutputRules(),
            'memory' => $this->memory->asString(),
            'tools' => $toolNames,
        ])->render();
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        //
    }

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booting()
    {
        //
    }

    public function handle(?array $input): array
    {
        $response = $this->getAnswer($input);

        return $this->doValidate($response);
    }

    public function getAnswer(?array $input): string
    {
        while (true) {
            $this->memory->load();

            $prompt = $this->getPrompt($input);
            $chatResponse = $this->integration->handle($prompt, $this->registered_tools);

            switch ($chatResponse->finishReason()) {
                case ResponseType::TOOL_CALL:
                    $this->handleTools($chatResponse);
                    break;
                case ResponseType::STOP:
                    return $chatResponse->content();
                default:
                    dd($chatResponse);
            }
        }
    }

    private function handleTools(MessageValueObject $message): void
    {

        if (empty($message->toolCalls())) {
            $messageData = [
                'role' => $message->role(),
                'content' => $message->content(),
            ];

            $this->memory->create(MessageValueObject::make($messageData));
        }

        if (! empty($message->toolCalls()) && count($message->toolCalls()) > 0) {
            foreach ($message->toolCalls() as $toolCall) {
                $this->executeToolCall($toolCall);
            }
        }
    }

    private function executeToolCall($toolCall): void
    {
        try {
            $toolResponse = $this->call(
                $toolCall['function']['name'],
                json_decode($toolCall['function']['arguments'], true, 512, JSON_THROW_ON_ERROR)
            );

            $this->memory->create(MessageValueObject::make([
                'role' => 'tool',
                'tool_call_id' => $toolCall['id'],
                'tool_name' => $toolCall['function']['name'],
                'tool_arguments' => $toolCall['function']['arguments'],
                'content' => $toolResponse,
            ]));
        } catch (Exception $e) {
            throw new Exception("Error calling tool: {$e->getMessage()}");
        }
    }
}
