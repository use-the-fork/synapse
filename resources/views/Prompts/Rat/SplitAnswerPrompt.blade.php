<message type="user">
## User input: {{ $question }}

## Response
{{ $answer }}

## Instruction
Split the answer of the question into multiple paragraphs with each paragraph containing a complete thought.
The answer should be splited into less than {{ $number_of_paragraphs }} paragraphs.

@include('synapse::Parts.OutputSchema')
</message>
