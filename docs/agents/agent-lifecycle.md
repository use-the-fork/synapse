# Agent Lifecycle

One of the most powerful features of Synapse is the Agent Lifecycle, which governs how an agent executes its tasks and allows for modification at various stages.

## `PendingAgentTask`

The `PendingAgentTask` class drives the agent's execution and stores all information about the current run. This class provides several public methods that can be accessed and modified:

- **`addInput(string $key, mixed $value)`**: Adds an input to the array used to generate the prompt.
- **`agent()`**: Returns the agent instance.
- **`currentIteration()`**: Returns the `CurrentIteration` object containing the raw response and message.
- **`inputs()`**: Retrieves the current input array.
- **`getInput(string $key)`**: Retrieves a specific input by key.
- **`iterationMemory()`**: Accesses the agent's memory for the current task.
- **`tools()`**: Retrieves the tools available to the agent.

## Lifecycle

The agent lifecycle is divided into the following stages:

### Initialize Agent

The agent is set up, and all hooks are initialized.

> To modify the agent at this stage, use the `HasBootAgentHook` in the [Hook Traits section](/traits/hook-trait).

### Code Calls the `handle` Method

The `handle` method is invoked, a new `CurrentIteration` is created, and the `inputs` and `extraAgentArgs` are set.

```php
$simpleAgent = new SimpleAgent;
$result = $agent->handle(['input' => 'hello!'], ['model' => 'gpt-4o-mini']); // [!code highlight]
```

> To modify the agent at this stage, use the `HasStartThreadHook` in the [Hook Traits section](/traits/hook-trait).

### Start 'Iteration Loop'

The 'Iteration Loop' begins by executing the `StartIterationPipeline`. Each loop iteration triggers all pipelines, which are executed multiple times if the agent calls multiple tools.

> To modify the agent at this stage, use the `HasStartIterationHook` in the [Hook Traits section](/traits/hook-trait).

### Get Prompt

The agent combines the view with inputs to create the prompt.

> To modify the agent at this stage, use the `HasPromptGeneratedHook` in the [Hook Traits section](/traits/hook-trait).

### Parse the Prompt

The prompt is parsed into messages based on [message tags](/prompts/).

> To modify the agent at this stage, use the `HasPromptParsedHook` in the [Hook Traits section](/traits/hook-trait).

### Call the Integration

Messages are sent to the integration, and a response is awaited.

> To modify the agent at this stage, use the `HasIntegrationResponseHook` in the [Hook Traits section](/traits/hook-trait).

### Tool Calls

If the integration response includes a tool call, it is parsed and sent to the tool. Two hooks are available to modify the `PendingAgentTask`—one before tool execution and one after.

> To modify the agent at this stage, use the `HasStartToolCallHook` or the `HasEndToolCallHook` in the [Hook Traits section](/traits/hook-trait).

### Integration Called "Stop"

This marks the end of the iteration loop when the agent finishes.

> To modify the agent at this stage, use the `HasAgentFinishHook` in the [Hook Traits section](/traits/hook-trait).

### End 'Iteration Loop' but Loop Again

If the agent needs to repeat the loop due to tool calls, this hook is invoked before the loop restarts.

> To modify the agent at this stage, use the `HasEndIterationHook` in the [Hook Traits section](/traits/hook-trait).

### End Thread

Before returning the final response via `handle`, this is the last opportunity to modify the result. If the `ValidatesOutputSchema` trait is used, it performs most of its work here.

> To modify the agent at this stage, use the `HasEndThreadHook` in the [Hook Traits section](/traits/hook-trait).
