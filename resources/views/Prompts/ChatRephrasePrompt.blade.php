<message type="system">
### Instruction
Given the following conversation and a follow up question, rephrase the follow up question to be a standalone
question.

@include('synapse::Parts.OutputSchema')
</message>

@include('synapse::Parts.MemoryAsMessages')

<message type="user">
    @include('synapse::Parts.Input')
</message>
