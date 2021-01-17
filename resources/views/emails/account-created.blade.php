@component('mail::message')
# Hello {{ $name }},

Since you have used Discord to register we have provided you with your password to use with the launcher.

PLEASE CHANGE THIS PASSWORD!

## Password
```
{{ $password }}
```


@component('mail::button', ['url' => route('password.request')])
Change Password
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
