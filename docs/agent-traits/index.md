# Agent Traits

To make magaing various agent activities easier Synapse provides `traits` that can be added to your agent. These hooks will modify the `PendingAgentTask` during the agent lifecycle.

## Available Traits

* `ManagesHooks` - This trait will add hooks at the Agent level to allow you to modify any part of the `PendingAgentTask` at will. THis is a good solution for anyone looking to do somthing that no Trait can do but also dosen't want to build there own Trait Plugin. See the [ManagesHooks section](/agent-traits/manages-hooks) for more detail.
