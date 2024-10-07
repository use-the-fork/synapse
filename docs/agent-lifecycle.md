# Agent Lifecycle

One of the most important and useful parts of Synapse is the Agent Lifecycle. That is how an agent executes its calls and our ability to modify them at each step. 

## `PendingAgentTask`
The primary driver for this is the `PendingAgentTask` class that is passed thru the agent and the pipelines that are hooked in to. The `PendingAgentTask` stores all information about the current run. The `PendingAgentTask` has the following public methods you can access and modify:

* `addInput` - Adds an input to the inputs array that is used to generate the prompt.
* `agent()` - Gets the Agent itself.
* `currentIteration()` - Gets the `CurrentIteration` object that has the current raw response, and message.
* `inputs()` - Gets the current input array.
* `getInput(string $key)` - Gets an input using it's key.
* `memory()` - Gets the current memory of the agent
* `tools()` - Gets the Tools the agent can use.

## Lifecycle
The agent lifecycle is broken up as follows:

### Initialize Agent
This is where the agent is setup and all of the hooks are booted.

> If you would like to hook in to or modify the agent at this step see the `HasBootAgentHook` in the [Hook Traits section](/agent-traits/hook-trait).

### Code calls the `handle` method of the agent.
At this point a new `CurrentIteration` is set and the `inputs` passed to `handle` are set. 

> If you would like to hook in to or modify the agent at this step see the `HasStartThreadHook` in the [Hook Traits section](/agent-traits/hook-trait).

### Start 'Iteration Loop' 
The 'Iteration Loop' starts by executing the `StartIterationPipeline`. Keep in mind that all pipelines inside of the loop are called at each iteration of the loop. For example if an agent calls 3 tools the Pipelines will be executed 3 times.

> If you would like to hook in to or modify the agent at this step see the `HasStartIterationHook` in the [Hook Traits section](/agent-traits/hook-trait).

### Get Prompt
The first thing that happens in the loop is the view and your inputs are combined to create the 'Prompt'.

> If you would like to hook in to or modify the agent at this step see the `HasPromptGeneratedHook` in the [Hook Traits section](/agent-traits/hook-trait).

### Parse the Prompt
Once the agent has the prompt in text format it then needs to be broken in to messages. This happends by parsing the prompt for its [message tags](/prompts).

> If you would like to hook in to or modify the agent at this step see the `HasPromptParsedHook` in the [Hook Traits section](/agent-traits/hook-trait).

### Call the integration
Now the messages are sent to your chosen integration and we wait for a response.

> If you would like to hook in to or modify the agent at this step see the `HasIntegrationResponseHook` in the [Hook Traits section](/agent-traits/hook-trait).

### Tool Calls
If the integration responded with a tool call the response is parsed and passed to the tool. However, Synapis offers two hooks to modify the `PendingAgentTask` one before a tool execution and one after.

> If you would like to hook in to or modify the agent at this step see the `HasStartToolCallHook` or the `HasEndToolCallHook` in the [Hook Traits section](/agent-traits/hook-trait).

### Integration called "Stop"
This is technically the 'end' of the loop. Since this is the only way for the loop to return.

> If you would like to hook in to or modify the agent at this step see the `HasAgentFinishHook` in the [Hook Traits section](/agent-traits/hook-trait).

### End 'Iteration Loop' but loop again.
If the agent needs to loop again because of a complete tool call etc. Then this will be called at the end of the loop right before the loop starts again.

> If you would like to hook in to or modify the agent at this step see the `HasEndIterationHook` in the [Hook Traits section](/agent-traits/hook-trait).

### End Thread
Just before returning a final response via the `handle` method. There is one last opportunity to modify the response. If you are using the `ValidatesOutputSchema` trait this is where it does most of it's work.

> If you would like to hook in to or modify the agent at this step see the `HasEndThreadHook` in the [Hook Traits section](/agent-traits/hook-trait).
