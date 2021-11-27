@component('mail::message')

# You have invited {{$user_name}} to collaborate on a program

{{$user_name}} is now a collaborator on the program: {{$program_title}} from the Department of {{$program_dept}}

@component('mail::button', ['url' => env('LOGIN_URL')])
Log In and See Program
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent