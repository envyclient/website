@component('mail::message')
# New Subscription

Thank you for subscribing to a plan {{ $user->name }}!

@component('mail::table')
| Plan | Interval | Price |
| :--: | :------: | :---: |
| {{ $user->subscription->plan->name }} | 30 days | {{ $user->subscription->plan->price }} |
@endcomponent

@component('mail::button', ['url' => url('/')])
Visit Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
