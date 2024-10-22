@if(!empty($errors))
## The following error occurred in the last response:
{!! $errors !!}
@endif

@if(!empty($lastResponse))
### Start Last Response ###
{!! $lastResponse !!}
### End Last Response ###
@endif

@include('synapse::Parts.OutputSchema')
