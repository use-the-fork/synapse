# Parts

Synapse comes packaged with some useful prompt parts that you can include in your own prompts.

## Input

This will take the array key `input` and insert it where you put the include. It will also attach an image if one was passed in to this user message.

```bladehtml
@include('synapse::Parts.Input')
```

## OutputSchema

This will add the required output rules to your prompt. 

This will take the array key `input` and insert it where you put the include. It will also attach an image if one was passed in to this user message.

```bladehtml
@include('synapse::Parts.OutputSchema')
```
