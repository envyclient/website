@component('mail::message')
# Hello {{ $user->name }},

Your media license request has been denied. 😢

## Reason
```
{{ $message }}
```

@component('mail::button', ['url' => route('dashboard')])
Dashboard
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
