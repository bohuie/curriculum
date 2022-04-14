@component('mail::message')

# You have been invited to collaborate on the course: {{$course_code}} {{$course_num}}.

{{$user_name}} has invited you to collaborate with them on their course: {{$course_code}} {{$course_num}} - {{$course_title}}.

@component('mail::button', ['url' => env('LOGIN_URL')])
Log In and See Course
@endcomponent
<br>
{{ config('app.name') }}
@endcomponent
