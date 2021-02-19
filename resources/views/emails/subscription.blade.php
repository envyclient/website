@component('mail::message')
# Hello {{ $user->name }},

Thank you for subscribing to the {{ $user->subscription->plan->name }} plan!

@component('mail::table')
| Plan | Interval | Price |
| :--: | :------: | :---: |
| {{ $user->subscription->plan->name }} | 30 days | {{ $user->subscription->plan->price }} |
@endcomponent

@component('mail::button', ['url' => route('home.subscription')])
Manage Subscription
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
