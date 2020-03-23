@component('mail::message')

@if($estimate->logo_image)
<img src="{{ $estimate->logo_image }}" alt="Logo" width="100"/>
@endif

# {{ $estimate->name }}

You received a new estimate. Please click the button below to see the estimate:

@component('mail::button', ['url' => $estimate->share_url])
Open Estimate
@endcomponent

Or access using the direct link: [{{ $estimate->share_url }}]({{ $estimate->share_url }})

Regards<br>
@endcomponent
