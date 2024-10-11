# Contextual Retrieval Preprocessing

The `ContextualRetrievalPreprocessingAgent` is a direct implementation of [Anthropic's contextual retrieval](https://www.anthropic.com/news/contextual-retrieval). This agent takes a document and a chunk from the document and returns a concise context to situate the chunk within the overall document, improving search accuracy.

## Usage

To use this agent, include it in your code as shown below. The `handle` method requires two inputs:
- **`document`**: The full page or document.
- **`chunk`**: The chunk of the document for which to create a succinct context.

```php
<?php

use UseTheFork\Synapse\Agents\ContextualRetrievalPreprocessingAgent;
use UseTheFork\Synapse\Integrations\Connectors\OpenAI\Requests\ChatRequest;

$agent = new ContextualRetrievalPreprocessingAgent;

$agentResponse = $agent->handle([
    'document' => "//! Executor for differential fuzzing.\n//! It wraps two executors that will be run after each other with the same input...\n",
    'chunk' => "//! Executor for differential fuzzing.\n//! It wraps two executors that will be run after each other with the same input...\n"
]);
```

### Example Output

The code above would produce the following output:

```php
array:3 [
  "role" => "assistant"
  "finish_reason" => "stop"
  "content" => array:1 [
    "succinct_context" => "This chunk introduces the DiffExecutor struct, which plays a central role in the differential fuzzing system by wrapping two executors. The primary and secondary executors are designed to run sequentially with the same input to differentiate their behavior."
  ]
]
```
