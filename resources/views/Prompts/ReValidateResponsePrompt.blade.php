@if(!empty($errors))
## The following error occurred in the last response:
{!! $errors !!}
@endif

### Response Format
Return all responses exclusively in JSON format following this structure:
{!! $outputRules !!}

Always ensure that the response adheres strictly to this format, as it will be used for API purposes.
