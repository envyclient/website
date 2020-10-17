@component('mail::message')
# New Subscription

Thank you for subscribing to the {{ $user->subscription->plan->name }} plan!

@component('mail::table')
| Plan | Interval | Price |
| :--: | :------: | :---: |
| {{ $user->subscription->plan->name }} | 30 days | {{ $user->subscription->plan->price }} |
@endcomponent

@component('mail::button', ['url' => url('subscriptions')])
Manage Subscription
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
