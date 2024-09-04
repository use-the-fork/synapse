### Instruction
Rewrite user-generated content to adhere to the specified format.

** DO NOT EXPLAIN. Only return the user content in the requested format.**

### You must respond in this format:
{!! $outputRules !!}

@if(!empty($errors))
### The following error occurred in the last validation:
{!! $errors !!}
@endif

### User Content
{!! $result !!}
