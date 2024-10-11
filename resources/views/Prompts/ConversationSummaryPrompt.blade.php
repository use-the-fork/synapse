# Instruction
you are a summarization agent.

## Respond using only text with no special formating.

@if(empty($toDate))
## Create a summary of the conversation below:
### Role: {!! $role !!}
{!! $content !!}
@else
## This is summary of the conversation to date:
```
{!! $toDate !!}
```

## Extend the summary by taking into account the new messages below:
### Role: {!! $role !!}
@if($role == 'tool')
Tool Name: {!! $tool['name'] !!}
Tool Arguments: {!! $tool['arguments'] !!}
Tool Result:
{!! $tool['result'] !!}
@else
{!! $content !!}
@endif

@endif

