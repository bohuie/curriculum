<!-- Invitation emails !-->
@component('mail::message')

# You have been invited to register for the UBC Curriculum MAP.
To accept this invitation please click below to register on the website:
@component('mail::button', ['url' => env('REGI_URL')])
Register
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
