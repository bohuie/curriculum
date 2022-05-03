@component('mail::message')

# {{$user_name}} has created a course for you on the UBC Curriculum MAP Tool

{{$user_name}} has created and given you ownership of the course: {{$course_code}} {{$course_num}} - {{$course_title}}.

This course has been added to the program: {{$program}}, and can now be mapped to the program.

{{$user_name}} will not be able to access this course unless you add them as a colloaborator.
<br>

@component('mail::button', ['url' => config('app.login_url')])
Log In and See Course
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent