# Parts

Synapse comes with several useful prompt parts that can be included in your own prompts.

## Input

This part inserts the `input` array key into the prompt. It will also attach an image if one was passed with the user's message.

```blade
@include('synapse::Parts.Input')
```

## OutputSchema

This part adds the required output rules to your prompt and is intended to be used with the [`ValidatesOutputSchema` trait](/traits/validates-output-schema).

```blade
@include('synapse::Parts.OutputSchema')
```

## Memory As Messages

This part adds the memory to your prompt, split up by individual messages. For more information, see the [`ManagesMemory` trait](/memory/index).

```blade
@include('synapse::Parts.MemoryAsMessages')
```

## Memory

This part adds the memory to your prompt as a single text block. For more information, see the [`ManagesMemory` trait](/memory/index).

```blade
@include('synapse::Parts.Memory')
```

## Tool List

To explicitly list available tools as part of the prompt, you can use this part. For more information about tools, see the [tools section](/tools/index).

```blade
@include('synapse::Parts.ToolList')
```
