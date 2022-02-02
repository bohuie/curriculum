@component('mail::message')

# You have invited {{$user_name}} to collaborate on a syllabus

{{$user_name}} is now a collaborator on the syllabus: {{$syllabus_code}} {{$syllabus_num}} - {{$syllabus_title}}.

@component('mail::button', ['url' => 'http://127.0.0.1:8000/login'])
Log In and See Syllabus
@endcomponent
<br>
{{ config('app.name') }}
@endcomponent
