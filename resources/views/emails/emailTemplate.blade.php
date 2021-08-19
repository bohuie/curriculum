@component('mail::message')


# {{ $title }}

Dear {{$name}},

<br>{!! $body !!}<br>


{{ $signature }}<br>

The UBC Curriculum MAP Team
@endcomponent
