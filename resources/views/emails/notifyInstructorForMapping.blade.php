@component('mail::message')

# {{$program_user_name}} has added your course to {{$program}}

Your course {{$course_code}} {{$course_num}} - {{$course_title}} has been included as part of this program. Please follow the below steps to map your course to {{$program}}.

**Steps:**

1. Log in using your UBC email address
2. Find the course on your dashboard
3. Go to “Program Outcome Mapping or Step 5” to map the course learning outcomes to the program learning outcomes
4. When your mapping is complete, be sure to click the save button to share your mapping with the program owner/editor  

@component('mail::button', ['url' => config('app.login_url')])
Log in and map course
@endcomponent
<br>
{{ config('app.name') }}
@endcomponent
