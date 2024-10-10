# Chat Rephrase Agent

The `ChatRephraseAgent` generates a standalone question based on the conversation history and the current input. This is particularly useful when conducting searches in a vector store or on the web.

## Usage

To use this agent, include it in your code as shown below. The `handle` method requires one input:
- **`input`**: The follow-up question.

```php
<?php

use UseTheFork\Synapse\Agents\ChatRephraseAgent;
use UseTheFork\Synapse\Constants\Role;
use UseTheFork\Synapse\Enums\FinishReason;
use UseTheFork\Synapse\ValueObject\Message;

$agent = new ChatRephraseAgent;
$agent->addMessageToMemory(Message::make([
    'role' => Role::USER,
    'content' => 'What gym activities do you recommend?',
]));

$agent->addMessageToMemory(Message::make([
    'role' => Role::ASSISTANT,
    'finish_reason' => FinishReason::STOP->value,
    'content' => 'For a balanced gym routine, I recommend incorporating a mix of cardio, strength training, and flexibility exercises. Start with 20-30 minutes of cardiovascular activities like running on the treadmill, cycling, or using the elliptical to improve heart health and endurance. Follow this with strength training exercises, such as weightlifting or bodyweight exercises like squats, deadlifts, bench presses, and pull-ups, to build muscle and enhance metabolism. Finish with 10-15 minutes of stretching or yoga to improve flexibility, reduce the risk of injury, and aid muscle recovery. This combination ensures a comprehensive workout that targets overall fitness, strength, and flexibility.',
]));

$agentResponse = $agent->handle(['input' => 'improve heart health?']);
```

### Example Output

The above code will produce the following output:

```php
array:3 [
  "role" => "assistant"
  "finish_reason" => "stop"
  "content" => array:1 [
    "standalone_question" => "How can I improve my heart health?"
  ]
]
```
