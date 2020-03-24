@component('mail::message')

@if($estimate->logo_image)
<img src="{{ $estimate->logo_image }}" alt="Logo" width="100"/>
@endif

# {{ $estimate->name }}

@lang('app.mail.received_estimate')

@component('mail::button', ['url' => $estimate->share_url])
@lang('app.mail.open_estimate')
@endcomponent

@lang('app.mail.access_using_link') [{{ $estimate->share_url }}]({{ $estimate->share_url }})

@lang('app.mail.regards')<br>
@endcomponent
