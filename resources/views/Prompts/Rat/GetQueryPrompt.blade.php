<message type="user">
## User input: {{ $question }}

## Response
{{ $answer }}

## Instruction
Summarize the content with a focus on the last sentences to create a concise search query for Bing. Use search syntax to make the query specific and relevant for content verification.

@include('synapse::Parts.OutputSchema')
</message>
