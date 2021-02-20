@component('mail::message')
# Hello {{ $name }},

Since you have used Discord to register we have provided you with your password.

PLEASE CHANGE THIS PASSWORD!

## Password
```
{{ $password }}
```


@component('mail::button', ['url' => route('home.profile')])
Manage Profile
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
