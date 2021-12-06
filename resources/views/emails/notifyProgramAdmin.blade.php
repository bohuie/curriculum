@component('mail::message')

# You have been invited to collaborate on a program
@if ($program_dept)
    {{$user_name}} has invited you to collaborate on the program: {{$program_title}} from the Department of {{$program_dept}}
@else 
    {{$user_name}} has invited you to collaborate on the program: {{$program_title}}
@endif


@component('mail::button', ['url' => env('LOGIN_URL')])
Log In and See Program
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
