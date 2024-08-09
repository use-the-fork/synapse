<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Closure;
use UseTheFork\Synapse\Integrations\Contracts\ModelIntegration;
use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\OutputParsers\Contracts\OutputParser;
use UseTheFork\Synapse\SystemPrompts\Contracts\SystemPrompt;
use UseTheFork\Synapse\Traits\HasTools;
use UseTheFork\Synapse\ValueObject\MessageValueObject;

class AgentExecutor
{
    use HasTools;

    public function __construct(
        public ModelIntegration $integration,
        public SystemPrompt $systemPrompt,
        public Memory $memory,
        public OutputParser $outputParser,
        public array $tools = []
    ) {

        $this->register($tools);
    }

    public function __invoke(?string $message): mixed
    {
      $response = $this->getAnswer($message);

      return $this->doValidate(
        $response,
        function($response) {
          return $this->outputParser->invoke($response);
        },
         $this->outputParser->getOutputFormat()
      );

    }

    public function getAnswer(?string $message): string
    {

        if ($message !== null) {
            $this->memory->create(MessageValueObject::make([
                'role' => 'user',
                'content' => $message,
            ]));
        }

        $this->memory->load();

      $this->systemPrompt->setOutputFormat($this->outputParser->getOutputFormat());

        $chatResponse = $this->integration->__invoke(
            $this->systemPrompt->get(),
            $this->memory->get(),
            $this->registered_tools
        );

        return $this->handleTools($chatResponse);
    }

    private function handleTools(MessageValueObject $message): string
    {

        $answer = $message->content();

        $messageData = [
            'role' => $message->role(),
            'content' => $message->content(),
        ];

        if (! empty($message->toolCalls()) && count($message->toolCalls()) > 0) {
            $messageData['tool_calls'] = $message->toolCalls();
        }

        $this->memory->create(MessageValueObject::make($messageData));

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

            $this->memory->create(MessageValueObject::make([
                'role' => 'tool',
                'tool_call_id' => $toolCall['id'],
                'name' => $toolCall['function']['name'],
                'content' => $toolResponse,
            ]));
        } catch (Exception $e) {
            throw new Exception("Error calling tool: {$e->getMessage()}");
        }
    }

    protected function doValidate(string $response, Closure $validation, string $expectedResponseFormat)
    {
      while (TRUE) {
        $result = $validation($response);
        if (!empty($result)) {
          return $result;
        }
        $response = $this->doRevalidate($response, $expectedResponseFormat);
      }
    }

    protected function doRevalidate(string $result, $expectedResponseFormat = NULL)
    {

      $prompt = [
        'role'    => 'user',
        'content' => "###Instruction###\nRewrite user-generated content to adhere to a specified format.\n\n{$expectedResponseFormat}\n\n###User Content###\n{$result}",
      ];

      return $this->integration->__invoke(
        "",
        $prompt
      );
    }

}
