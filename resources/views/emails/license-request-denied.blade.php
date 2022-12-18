@component('mail::message')
# Hello {{ $user->name }},

Your media license request has been denied. 😢

## Reason
```
{{ $message }}
```

@component('mail::button', ['url' => route('home')])
Dashboard
@endcomponent

Don't worry you can apply again.

Regards,<br>
{{ config('app.name') }}
@endcomponent
