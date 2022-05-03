@component('mail::message')

# You have invited {{$user_name}} to collaborate on a course

{{$user_name}} is now a collaborator on the course: {{$course_code}} {{$course_num}} - {{$course_title}}.

@component('mail::button', ['url' => config('app.login_url')])
Log In and See Course
@endcomponent
<br>
{{ config('app.name') }}
@endcomponent
