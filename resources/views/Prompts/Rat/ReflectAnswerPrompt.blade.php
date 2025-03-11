<message type="user">
    ### Question: {{ $question }}

    ### Answer:
    {{ $answer }}

    ### Instruction
    Give a title for the answer of the question.
    And add a subtitle to each paragraph in the answer and output the final answer using markdown format.
    This will make the answer to this question look more structured for better understanding.

    **IMPORTANT**
    Try to keep the structure (multiple paragraphs with its subtitles) in the response and make it more structural for understanding.

    @include('synapse::Parts.OutputSchema')
</message>
