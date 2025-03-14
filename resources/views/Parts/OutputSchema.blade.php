@isset($outputSchema)
### Mandatory Response Format
Return all responses exclusively in JSON format following this structure with out **ANY** surrounding text:
{!! $outputSchema !!}

Always ensure that the response adheres strictly to this format, as it will be used for API purposes.

### Start Response ###
```json
@endisset
