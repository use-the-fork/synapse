<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Agents;

use Exception;
use OpenAI;
use OpenAI\Client;
use UseTheFork\Synapse\Models\Agent;
use UseTheFork\Synapse\Traits\HasTools;
use UseTheFork\Synapse\ValueObject\MessageValueObject;
use UseTheFork\Synapse\ValueObject\ToolCallValueObject;

class BaseAgent
{
    use HasTools;

    protected Client $client;

    protected Agent $agent;

    public function getAnswer(?string $message): string
    {

        if ($message !== null) {
            $this->agent->messages()->create([
                'role' => 'user',
                'content' => $message,
            ]);
        }

        $chatRequest = $this->generateRequestBody();
        $response = $this->createDtoFromResponse(
            $this->client->chat()->create($chatRequest)
        );

        return $this->handleTools($response);
    }

    public function createDtoFromResponse(OpenAI\Responses\Chat\CreateResponse $response): MessageValueObject
    {
        $data = $response->toArray();
        $message = $data['choices'][0]['message'] ?? [];
        $tools = collect([]);
        if (isset($message['tool_calls'])) {
            foreach ($message['tool_calls'] as $toolCall) {
                $tools->push(ToolCallValueObject::make($toolCall));
            }
            $message['tool_calls'] = $tools;
        }

        return MessageValueObject::makeOrNull($message);
    }

    private function handleTools($message): string
    {

        $answer = $message->content();

        $messageData = [
            'role' => $message->role(),
            'content' => $message->content(),
        ];

        if (! empty($message->toolCalls()) && count($message->toolCalls()) > 0) {
            $messageData['tool_calls'] = $message->toolCalls();
        }

        $this->agent->messages()->create($messageData);

        if (! empty($message->toolCalls()) && count($message->toolCalls()) > 0) {

            foreach ($message->toolCalls() as $toolCall) {
                $this->executeToolCall($toolCall);
            }

            return $this->getAnswer(null);
        }

        return $answer;
    }

    private function executeToolCall($toolCall): void
    {
        try {
            $toolResponse = $this->call(
                $toolCall['function']['name'],
                json_decode($toolCall['function']['arguments'], true, 512, JSON_THROW_ON_ERROR)
            );

            $this->agent->messages()->create([
                'role' => 'tool',
                'tool_call_id' => $toolCall['id'],
                'name' => $toolCall['function']['name'],
                'content' => $toolResponse,
            ]);
        } catch (Exception $e) {
            throw new Exception("Error calling tool: {$e->getMessage()}");
        }
    }

    /**
     * Data to be sent in the body of the request
     */
    public function generateRequestBody(): array
    {
        $messages = [[
            'role' => 'system',
            'content' => $this->agent->prompt,
        ]];

        foreach ($this->agent->messages as $message) {
            $messages[] = [
                'role' => $message->role,
                'content' => $message->content,
            ];

            if ($message->tool_calls) {
                $messages[count($messages) - 1]['tool_calls'] = $message->tool_calls;
            }
        }

        $payload = [
            'model' => $this->agent->model,
            'messages' => $messages,
        ];

        if (! empty($this->agent->tools)) {
            $payload['tools'] = array_values($this->registered_tools);
        }

        return $payload;
    }
}
