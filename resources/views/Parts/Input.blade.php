@isset($input)
@if (!empty($image))
<message type="image_url" image="{!! $image !!}">
@else
<message type="user">
@endif
{{$input}}
</message>
@endisset
