@component('mail::message')

# You have invited {{$user_name}} to collaborate on a program

{{$user_name}} is now a collaborator on the program: {{$program_title}}.

@component('mail::button', ['url' => config('app.login_url')])
Log In and See Program
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent