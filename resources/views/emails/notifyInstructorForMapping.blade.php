@component('mail::message')

# You have been asked you to map your course to the program: {{$program}}

{{$program_user_name}} has asked you to map your course: {{$course_code}} {{$course_num}} - {{$course_title}}, to the program: {{$program}}.

@component('mail::button', ['url' => env('LOGIN_URL')])
Log In and Map Course
@endcomponent
<br>
{{ config('app.name') }}
@endcomponent
