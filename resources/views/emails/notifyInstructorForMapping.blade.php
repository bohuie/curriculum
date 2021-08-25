@component('mail::message')

# {{$program_user_name}} has added your course to {{$program}}

Your course {{$course_code}} {{$course_num}} - {{$course_title}} has been identified as {{$required}}. Please log into your account to map your course to the learning outcomes of {{$program}}.

@component('mail::button', ['url' => env('LOGIN_URL')])
Log In and Map Course
@endcomponent
<br>
{{ config('app.name') }}
@endcomponent
