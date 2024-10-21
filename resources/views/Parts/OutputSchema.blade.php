@isset($outputSchema)
### Response Format
Return all responses exclusively in JSON format following this structure:
{!! $outputSchema !!}

Always ensure that the response adheres strictly to this format, as it will be used for API purposes.
@endisset
