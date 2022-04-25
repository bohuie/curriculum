@component('mail::message')

# You have been invited by {{$user_name}} to use UBC Curriculum MAP Tool

{{$user_name}} has invited you to collaborate on the program: {{$program_title}}.
<br>
# Login Information:
<table align="center" style="border:none; font-size:medium;">
    <tr>
        <th>
            Email:
        </th>
        <td style="padding-left: 10%;">
            {{$email}}
        </td>
    </tr>
    <tr>
        <th>
            Password:
        </th>
        <td style="padding-left: 10%;">
            {{$pass}}
        </td>
    </tr>
</table>

@component('mail::button', ['url' => config('app.login_url')])
Log In and See Program
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent