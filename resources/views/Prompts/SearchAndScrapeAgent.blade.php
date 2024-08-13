<message type="system">
	### Instruction
	Use tools to complete the users research task.

	@include('synapse::Parts.OutputRules')
</message>


@include('synapse::Parts.MemoryAsMessages')
@include('synapse::Parts.Input')
