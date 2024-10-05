# Working With Agents

Once you have created your first Agent you can start sending requests and communicating with it.

## Agent Lifecycle

All Agents have a lifecycle they follow when initialized and when running a task.
At each stage of a Agents lifecycle Synapes allows you to view and modify the task via the `PendingAgentTask` class and "Pipelines" that are exacuted at the respective location. An agents lifecycle breaks down as follows:

## Boot

The `PendingAgentTask` is initialized and the `BootAgentPipeline` is executed.

## Handle

All Agents have a `handle` method that accepts an array of `inputs` and `extraAgentArgs`.

```php
  $simpleAgent = new SimpleAgent;
  $result = $agent->handle(['input' => 'hello!'], ['model' => 'gpt-4o-mini']);
```

In the above example a completion request is sent with the 'input' of hello and the default 'model' would be overwritten to use `gpt-4o-mini` for just the current Iteration Run.

The `handle` method kicks off the agent lifecycle. It will:

1. Reset the `currentIteration` class of the `PendingAgentTask`.
1. Replace the `inputs` and `extraAgentArgs` of the `PendingAgentTask` with the new ones passed to the `handle` method.
1. Executes the `StartThreadPipeline`.

## The Loop

Once the `handle` method is completed the actual agent starts its work. It does this by entering a loop that can end by either the agent reaching a `stop` finish reason or by the agent hitting its `maximumIterations` count.

1. When entering the Loop the `StartIterationPipeline` is executed.
1. The Prompt `view` is rendered in to plain text. For more on this see the [prompts section](/agents/prompts)
1. The returned view is then parsed in to messages.
1. The messages are sent to your selected integration and a new `Message` is returned.
1. The `IntegrationResponsePipeline` is executed.
1. If the agent is making a Tool Call continue to 7 otherwise the agent is finished and you may skip to 11.
1. The `StartToolCallPipeline` is executed.
1. The Tool makes it's call and a response is captured and set as the content in the `Message`
1. The `EndToolCallPipeline` is executed.
1. The `EndIterationPipeline` is executed and the Loop starts Again at Step 1.
1. If the agent integration responds with a `stop` then the `AgentFinishPipeline` is executed.

## End

The `EndThreadPipeline` is exectuted and a message is returned.
