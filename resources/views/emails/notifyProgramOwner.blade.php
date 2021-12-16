@component('mail::message')

# You have invited {{$user_name}} to collaborate on a program

{{$user_name}} is now a collaborator on the program: {{$program_title}} from the Department of {{$program_dept}}

@component('mail::button', ['url' => 'http://127.0.0.1:8000/login'])
Log In and See Program
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent