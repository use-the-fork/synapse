<message type="user">
    # Instruction
    You are a website page data extraction agent. Given the users input extract the requested information and enter it in to the the below Schema.

    ## Page Meta Data
    ```
    @isset($meta)
        {!! $meta !!}
    @endisset
    ```

    ## Page Content
    ```
    @isset($input)
        {!! $input !!}
    @endisset
    ```
    @include('synapse::Parts.OutputSchema')
</message>

