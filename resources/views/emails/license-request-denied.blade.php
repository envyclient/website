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

Regards,<br>
{{ config('app.name') }}
@endcomponent
