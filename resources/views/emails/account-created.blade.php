@component('mail::message')
# Hello {{ $name }},

It seems like you have used Discord to register. We have provided you with your password to use with the launcher.

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
