<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Exception;
use InvalidArgumentException;
use Throwable;
use UseTheFork\Synapse\Exceptions\UnknownFinishReasonException;
use UseTheFork\Synapse\Integrations\Concerns\HasIntegration;
use UseTheFork\Synapse\Integrations\Enums\ResponseType;
use UseTheFork\Synapse\Integrations\Enums\Role;
use UseTheFork\Synapse\Integrations\ValueObjects\Message;
use UseTheFork\Synapse\Integrations\ValueObjects\Response;
use UseTheFork\Synapse\Memory\Concerns\HasMemory;
use UseTheFork\Synapse\OutputSchema\Concerns\HasOutputSchema;
use UseTheFork\Synapse\Tools\Concerns\HasTools;
use UseTheFork\Synapse\Utilities\Concerns\HasLogging;

class Agent
{
    use HasIntegration,
        HasLogging,
        HasMemory,
        HasOutputSchema,
        HasTools;

    /**
     * a keyed array of values to be used as extra inputs that are passed to the prompt when it is generated.
     */
    protected array $extraInputs = [];

    /**
     * The view to use when generating the prompt for this agent
     */
    protected string $promptView;

    /**
     * Initializes the agent.
     *
     * This method is called upon object creation to initialize the agent.
     * It is responsible for performing any necessary setup tasks.
     *
     * @throws Throwable
     */
    public function __construct()
    {
        $this->initializeAgent();
    }

    /**
     * Initialize the agent by calling initialization methods for integration, memory, tools, and output rules.
     *
     * @throws Throwable
     */
    protected function initializeAgent(): void
    {
        $this->initializeIntegration();
        $this->initializeMemory();
        $this->initializeTools();
        $this->initializeOutputSchema();
    }

    /**
     * Handles the user input and extra agent arguments to retrieve the response.
     *
     * @param  array|null  $input  The input array.
     * @param  array|null  $extraAgentArgs  The extra agent arguments array.
     * @return array The validated response array.
     *
     * @throws Throwable
     */
    public function handle(?array $input, ?array $extraAgentArgs = []): array
    {
        $response = $this->getAnswer($input, $extraAgentArgs);

        $this->log('Start validation', [$response]);

        return $this->doValidate($response);
    }

    /**
     * @throws Throwable
     */
    protected function getAnswer(?array $input, ?array $extraAgentArgs = []): string
    {
        while (true) {
            $this->memory->load();

            $prompt = $this->parsePrompt(
                $this->getPrompt($input)
            );

            $this->log('Call Integration');

            // Create the Chat request we will be sending.
            $chatResponse = $this->integration->handleCompletion($prompt, $this->registered_tools, $extraAgentArgs);
            $this->log("Finished Integration with {$chatResponse->finishReason()}");

            switch ($chatResponse->finishReason()) {
                case ResponseType::TOOL_CALL:
                    $this->handleTools($chatResponse);
                    break;
                case ResponseType::STOP:
                    return $chatResponse->content();
                default:
                    throw new UnknownFinishReasonException("{$chatResponse->finishReason()} is not a valid finish reason.");
            }
        }
    }

    /**
     * Parses a prompt and extracts message blocks.
     *
     * @param  string  $prompt  The prompt view to parse.
     * @return array The extracted message blocks as an array of Message objects.
     *
     * @throws InvalidArgumentException If a message block does not define a type.
     * @throws Throwable If an error occurs during parsing.
     */
    protected function parsePrompt(string $prompt): array
    {

        $prompts = [];
        // Adjusted pattern to account for possible newlines, nested content, and the new 'image' attribute
        $pattern = '/<message\s+type=[\'"](?P<role>\w+)[\'"](?:\s+tool=[\'"](?P<tool>[\w\-+=\/]+)[\'"])?(?:\s+image=[\'"](?P<image>[\w\-+=\/]+)[\'"])?\s*>\s*(?P<message>.*?)\s*<\/message>/s';
        preg_match_all($pattern, $prompt, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $role = $match['role'] ?? null;
            $tool = $match['tool'] ?? null;
            $image = $match['image'] ?? null;
            $promptContent = $match['message'] ?? '';

            $promptContent = trim($promptContent);

            if (! $role) {
                throw new InvalidArgumentException("Each message block must define a type.\nExample:\n<message type='assistant'>Foo {bar}</message>");
            }
            $messageData = [
                'role' => $role,
                'content' => $promptContent,
            ];
            if ($tool) {
                $tool = json_decode(base64_decode($tool), true);
                $messageData['tool_call_id'] = $tool['id'];
                $messageData['tool_name'] = $tool['name'] ?? null;
                $messageData['tool_arguments'] = $tool['arguments'] ?? null;
                $messageData['tool_content'] = $tool['content'] ?? null;
            }
            if ($image) {
                $image = json_decode(base64_decode($image), true);
                // attach the image data to the message.
                $messageData['image'] = $image;
            }
            $prompts[] = Message::make($messageData);
        }

        if ($prompts === []) {
            // The whole document is a prompt
            $prompts[] = Message::make([
                'role' => Role::USER,
                'content' => trim($prompt),
            ]);
        }

        return $prompts;
    }

    /**
     * Retrieves the prompt view, based on the provided inputs.
     *
     * @param  array  $inputs  The inputs for the prompt.
     * @return string The rendered prompt view.
     *
     * @throws Throwable
     */
    public function getPrompt(array $inputs): string
    {
        $toolNames = array_keys($this->tools);

        if (isset($inputs['image'])) {
            $inputs['image'] = base64_encode(json_encode($inputs['image']));
        }

        return view($this->promptView, [
            ...$inputs,
            ...$this->extraInputs,
            // We return both Memory With Messages and without.
            ...$this->memory->asInputs(),
            'outputSchema' => $this->getOutputSchema(),
            'tools' => $toolNames,
        ])->render();
    }

    /**
     * Handles the AI response tool calls.
     *
     * @param  Response  $response  The response message object.
     *
     * @throws Throwable
     */
    private function handleTools(Response $response): void
    {

        $messageData = [
            'role' => $response->role(),
            'content' => $response->content(),
        ];

        if ($response->toolCall() !== []) {
            $toolCall = $response->toolCall();
            $toolResult = $this->executeToolCall($toolCall);

            // Append Message Data to Tool Call
            $messageData['role'] = 'tool';
            $messageData['tool_call_id'] = $toolCall['id'];
            $messageData['tool_name'] = $toolCall['function']['name'];
            $messageData['tool_arguments'] = $toolCall['function']['arguments'];
            $messageData['tool_content'] = $toolResult;
        }

        $this->memory->create(Message::make($messageData));

    }

    /**
     * Executes a tool call.
     *
     * This method is responsible for calling a tool function with the given arguments
     * and returning the result as a string.
     *
     * @param  array  $toolCall  The tool call data, containing the name of the function and its arguments.
     * @return string The result of the tool call.
     *
     * @throws Exception If an error occurs while calling the tool function.
     * @throws Throwable If JSON decoding of the arguments fails.
     */
    private function executeToolCall(array $toolCall): string
    {
        $this->log('Tool Call', $toolCall);

        try {
            return $this->call(
                $toolCall['function']['name'],
                json_decode($toolCall['function']['arguments'], true, 512, JSON_THROW_ON_ERROR)
            );

        } catch (Exception $e) {
            throw new Exception("Error calling tool: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
}
