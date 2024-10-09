# Parts

Synapse comes packaged with some useful prompt parts that you can include in your own prompts.

## Input

This will take the array key `input` and insert it where you put the include. It will also attach an image if one was passed in to this user message.

```bladehtml
@include('synapse::Parts.Input')
```

## OutputSchema

This part will add the required output rules to your prompt and is meant to be used in confjunction with the [`ValidatesOutputSchema` trait](/traits/validates-output-schema).

```bladehtml
@include('synapse::Parts.OutputSchema')
```

## Memory As Messages

This part will add the memory to your prompt but split up by messages to learn more see [`ManagesMemory` trait](/memory).

```bladehtml
@include('synapse::Parts.MemoryAsMessages')
```

## Memory

This part will add the memory to your prompt but one single text block to learn more see [`ManagesMemory` trait](/memory).

```bladehtml
@include('synapse::Parts.Memory')
```

## Tool List

If you would like to explicitly list avliable tools as part of the prompt you can use this part. To learn more about tools see the [tools section](/tools).

```bladehtml
@include('synapse::Parts.ToolList')
```
