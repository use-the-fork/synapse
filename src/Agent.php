<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Exception;
use Illuminate\Support\Facades\Log;
use UseTheFork\Synapse\Integrations\Enums\ResponseType;
use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Integrations\ValueObjects\Response;
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
        $this->fireAgentEvent('booting', false);
        $this->initializeAgent();
        $this->fireAgentEvent('booted', false);
    }

    protected function initializeAgent(): void
    {
        $this->initializeIntegration();
        $this->initializeMemory();
        $this->initializeTools();
        $this->initializeOutputRules();
    }

    public function getPrompt(array $inputs): string
    {
        $toolNames = [];
        foreach ($this->tools as $name => $tool) {
            $toolNames[] = $name;
        }

        if (isset($inputs['image'])) {
            $inputs['image'] = base64_encode(json_encode($inputs['image']));
        }

        return view($this->promptView, [
            ...$inputs,
            ...$this->extraInputs,
            // We return both Memory With Messages and without.
            ...$this->memory->asInputs(),
            'outputRules' => $this->getOutputRules(),
            'tools' => $toolNames,
        ])->render();
    }

    public function parsePrompt(string $prompt): array
    {

        $prompts = [];
        // Adjusted pattern to account for possible newlines, nested content, and the new 'image' attribute
        $pattern = '/<message\s+type=[\'"](?P<role>\w+)[\'"](?:\s+tool=[\'"](?P<tool>[\w\-+=\/]+)[\'"])?(?:\s+image=[\'"](?P<image>[\w\-+=\/]+)[\'"])?\s*>\s*(?P<message>.*?)\s*<\/message>/s';
        preg_match_all($pattern, $prompt, $matches, PREG_SET_ORDER);

        foreach ($matches as $promptBlock) {
            $role = $promptBlock['role'] ?? null;
            $tool = $promptBlock['tool'] ?? null;
            $image = $promptBlock['image'] ?? null;
            $promptContent = $promptBlock['message'] ?? '';

            $promptContent = trim($promptContent);

            if (! $role) {
                throw new \InvalidArgumentException("Each message block must define a type.\nExample:\n<message type='assistant'>Foo {bar}</message>");
            } else {
                $messageData = [
                    'role' => $role,
                    'content' => $promptContent,
                ];

                if ($tool) {
                    $tool = json_decode(base64_decode($tool), true);
                    $messageData['tool_call_id'] = $tool['id'];
                    if ($role == Role::ASSISTANT) {
                        $messageData['tool_name'] = $tool['name'] ?? null;
                        $messageData['tool_arguments'] = $tool['arguments'] ?? null;
                    }
                }

                if ($image) {
                    $image = json_decode(base64_decode($image), true);
                    if ($role == Role::USER) {
                        //since this is an image we convert the content to have both text and image URL.
                        $messageData['content'] = [
                            [
                                'type' => 'text',
                                'text' => $messageData['content'],
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => $image,
                            ],
                        ];

                    }
                }

                $prompts[] = Message::make($messageData);
            }
        }

        if (empty($prompts)) {
            // The whole document is a prompt
            $prompts[] = Message::make([
                'role' => Role::USER,
                'content' => trim($prompt),
            ]);
        }

        return $prompts;
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

    public function handle(?array $input, ?array $extraAgentArgs = []): array
    {
        $response = $this->getAnswer($input, $extraAgentArgs);

        $this->log('Start validation', [$response]);

        return $this->doValidate($response);
    }

    public function getAnswer(?array $input, ?array $extraAgentArgs = []): string
    {
        while (true) {
            $this->memory->load();

            $prompt = $this->parsePrompt(
                $this->getPrompt($input)
            );

            $this->log('Call Integration');
            $chatResponse = $this->integration->handle($prompt, $this->registered_tools, $extraAgentArgs);
            $this->log("Finished Integration with {$chatResponse->finishReason()}");

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

    private function handleTools(Response $responseMessage): void
    {

        if (empty($responseMessage->toolCalls())) {
            $messageData = [
                'role' => $responseMessage->role(),
                'content' => $responseMessage->content(),
            ];

            $this->memory->create(Message::make($messageData));
        }

        if (! empty($responseMessage->toolCalls()) && count($responseMessage->toolCalls()) > 0) {
            foreach ($responseMessage->toolCalls() as $toolCall) {
                $this->executeToolCall($toolCall);
            }
        }
    }

    private function executeToolCall($toolCall): void
    {
        $this->log('Tool Call', $toolCall);

        try {
            $toolResponse = $this->call(
                $toolCall['function']['name'],
                json_decode($toolCall['function']['arguments'], true, 512, JSON_THROW_ON_ERROR)
            );

            $this->memory->create(Message::make([
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

    protected function log(string $event, ?array $context = []): void
    {
        $class = get_class($this);
        Log::debug("{$event} in {$class}", $context);
    }
}
