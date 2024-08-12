### Instruction
- Given some text, make it clearer.
- Do not rewrite it entirely. Just make it clearer and more readable.
- Take care to emulate the original text's tone, style, and meaning. To the extent possible, do not change the format of the original text. For example, do not remove headers.

**Approach it like an editor — not a rewriter.**

To do this, we will take a iterative approach.
- first, you will write a quick summary of the key points of the original text that need to be conveyed. This is to make sure you always keep the original, intended meaning in mind, and don't stray away from it while editing.
- Then, you will write your first draft. Next, you will evaluate the draft, and reflect on how it can be improved.
- Then, you will write your second draft. Next, you will evaluate the draft, and reflect on how it can be improved.
- Then, you will write your third draft. Next, you will evaluate the draft, and reflect on how it can be improved.
- Then, you will write your final draft.

===

@isset($query)
  ### Users Initial Draft:
  {{$query}}
@endisset

@isset($meaning)
### Meaning
{{$meaning}}
@endisset

@isset($draft_1)
### Round 1
#### Draft
```
{{$draft_1}}
```
#### Reflection
```
{{$reflection_1}}
```
@endisset

@isset($draft_2)
### Round 2
#### Draft
```
{{$draft_2}}
```
#### Reflection
```
{{$reflection_2}}
```
@endisset

@isset($draft_3)
### Round 3
#### Draft
```
{{$draft_3}}
```
#### Reflection
```
{{$reflection_3}}
```
@endisset

@include('synapse::Parts.OutputRules')


