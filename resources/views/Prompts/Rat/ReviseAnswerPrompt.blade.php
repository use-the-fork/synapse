<message type="user">
    ### Existing Text in Wiki Web:
    ```
    {{ $content }}
    ```

    ### Question: {{ $question }}

    ### Answer:
    {{ $answer }}

    ### Instruction
    I want to revise the answer according to retrieved related text of the question in WIKI pages.
    You need to check whether the answer is correct.
    If you find some errors in the answer, revise the answer to make it better.
    If you find some necessary details are ignored, add it to make the answer more plausible according to the related text.
    If you find the answer is right and do not need to add more details, just output the original answer directly.

    **IMPORTANT**
    Try to keep the structure (multiple paragraphs with its subtitles) in the revised answer and make it more structural for understanding.
    Add more details from retrieved text to the answer.
    Split the paragraphs with \n\n characters.

    @include('synapse::Parts.OutputSchema')
</message>
