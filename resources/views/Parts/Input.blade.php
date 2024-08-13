@isset($input)
@if (!empty($image))
<message type="user" image="{!! $image !!}">
@else
<message type="user">
@endif
{{$input}}
</message>
@endisset
