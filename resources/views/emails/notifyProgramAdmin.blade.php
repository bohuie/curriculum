@component('mail::message')

# You have been invited to collaborate on a program

{{$user_name}} has invited you to collaborate on the program: {{$program_title}}

@component('mail::button', ['url' => 'https://curriculum.ok.ubc.ca/login'])
Log In and See Program
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
