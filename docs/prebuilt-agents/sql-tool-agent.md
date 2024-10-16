# SQL Tool Agent

The `SQLToolAgent` has access to the `ListSQLDatabaseTool`, `InfoSQLDatabaseTool`, and `QuerySQLDataBaseTool` tools. Given user input, it will use these tools to attempt to answer the provided question based on the database.

## Usage

To use this agent, include it in your code as shown below. The `handle` method requires one input:
- **`input`**: The question you would like answered based on the database.

```php
<?php

use UseTheFork\Synapse\Agents\SQLToolAgent;

$agent = new SQLToolAgent;
$agentResponse = $agent->handle(['input' => 'How many organizations are operating and what is the average number of funding rounds for them?']);
```

### Example Output

The above code will produce the following output:

```php
array:3 [
  "role" => "assistant"
  "finish_reason" => "stop"
  "content" => array:1 [
    "answer" => "There are 100 organizations currently operating, and the average number of funding rounds for them is 5."
  ]
] 
```
