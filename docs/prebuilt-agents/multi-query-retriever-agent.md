# Multi Query Retriever Agent

The `MultiQueryRetrieverAgent` generates different versions of a given user question. These variations can be used to retrieve relevant documents from a vector database or to have another agent perform a web search (e.g., using Google).

## Usage

To use this agent, simply include it as shown below. The `handle` method takes two inputs:
- **`queryCount`**: The number of query variations to generate.
- **`input`**: The question for which you want queries generated.

```php
<?php

use UseTheFork\Synapse\Agents\MultiQueryRetrieverAgent;

$agent = new MultiQueryRetrieverAgent;
$agentResponse = $agent->handle(['queryCount' => '5', 'input' => 'What gym activities do you recommend for heart health?']);
```

### Example Output

The above code results in the following output:

```php
array:3 [
  "role" => "assistant"
  "finish_reason" => "stop"
  "content" => array:1 [
    "answer" => array:5 [
      0 => "Which exercises at the gym are best for cardiovascular health?"
      1 => "What are the most effective gym workouts to improve heart function?"
      2 => "Can you suggest some gym routines that benefit heart health?"
      3 => "What types of gym activities are good for strengthening the heart?"
      4 => "Which fitness center exercises could help in enhancing heart health?"
    ]
  ]
]
```
