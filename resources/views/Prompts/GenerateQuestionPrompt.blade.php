<message type="user">
# Instruction
Given a answer @isset($meta) and the meta data for the answer @endisset. Generate a question for the answer.

@isset($meta)
    ### Document Meta Data
    {!! $meta !!}
@endisset

## Answer for Question
{!! $input !!}

@include('synapse::Parts.OutputSchema')
</message>
