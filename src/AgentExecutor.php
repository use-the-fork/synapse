<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Closure;
use UseTheFork\Synapse\Integrations\Contracts\ModelIntegration;
use UseTheFork\Synapse\Memory\Contracts\Memory;
use UseTheFork\Synapse\OutputParsers\Contracts\OutputParser;
use UseTheFork\Synapse\Prompts\Contracts\Prompt;
use UseTheFork\Synapse\Traits\HasTools;
use UseTheFork\Synapse\ValueObject\MessageValueObject;

class AgentExecutor
{
    use HasTools;

    public function __construct(
        public ModelIntegration $integration,
        public Prompt $prompt,
        public Memory $memory,
        public OutputParser $outputParser,
        public array $tools = []
    ) {

        $this->register($tools);
    }

    public function __invoke(?array $input): mixed
    {
      $response = $this->getAnswer($input);

      return $this->doValidate(
        $response,
        function($response) {
          return $this->outputParser->invoke($response);
        },
         $this->outputParser->getOutputFormat()
      );

    }

    public function getAnswer(?array $input): string
    {
      while(true){
        $this->memory->load();

        $prompt = $this->prompt->get(
          $input,
          $this->outputParser,
          $this->memory,
          $this->registered_tools
        );

        $chatResponse = $this->integration->__invoke($prompt, $this->registered_tools);

        switch ($chatResponse->finishReason()) {
          case "tool_calls";
            $this->handleTools($chatResponse);
            break;
          case "stop";
            return $chatResponse->content();
          default:
            dd($chatResponse);
        }
      }
    }

    private function handleTools(MessageValueObject $message): void
    {

      if ( empty($message->toolCalls()) ) {
        $messageData = [
          'role'    => $message->role(),
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
