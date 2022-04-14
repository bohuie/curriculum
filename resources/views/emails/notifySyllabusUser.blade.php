@component('mail::message')

# You have been invited to collaborate on the syllabus for the course: {{$syllabus_code}} {{$syllabus_num}}.

{{$user_name}} has invited you to collaborate with them on their syllabus: {{$syllabus_code}} {{$syllabus_num}} - {{$syllabus_title}}.

@component('mail::button', ['url' => env('LOGIN_URL')])
Log In and See Syllabus
@endcomponent
<br>
{{ config('app.name') }}
@endcomponent
