@extends('layouts.dash')

@section('title', 'Subscription')

@section('content')
    @if($user->subscription === null)

        {{-- User has no subscription --}}
        @foreach($plans as $plan)
            <x-plan-card class="mb-5" :plan="$plan">

                {{--Card Footer--}}
                <div
                    class="px-4 py-3 bg-gray-100 text-right group flex items-center justify-end space-x-1 flex flex-wrap">

                    {{-- Subscribe using PayPal --}}
                    <form action="{{ route('paypal.checkout') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $plan->id }}">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 1792 1792" fill="currentColor">
                                <path
                                    d="M1647 646q18 84-4 204-87 444-565 444h-44q-25 0-44 16.5t-24 42.5l-4 19-55 346-2 15q-5 26-24.5 42.5t-44.5 16.5h-251q-21 0-33-15t-9-36q9-56 26.5-168t26.5-168 27-167.5 27-167.5q5-37 43-37h131q133 2 236-21 175-39 287-144 102-95 155-246 24-70 35-133 1-6 2.5-7.5t3.5-1 6 3.5q79 59 98 162zm-172-282q0 107-46 236-80 233-302 315-113 40-252 42 0 1-90 1l-90-1q-100 0-118 96-2 8-85 530-1 10-12 10h-295q-22 0-36.5-16.5t-11.5-38.5l232-1471q5-29 27.5-48t51.5-19h598q34 0 97.5 13t111.5 32q107 41 163.5 123t56.5 196z"/>
                            </svg>
                            Paypal
                        </button>
                    </form>

                    {{-- Subscribe using Credit/Debit Card --}}
                    <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            onclick="stripeCheckout({{ $plan->id }})">
                        <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Credit Card
                    </button>

                    {{-- Purchase using Wechat Pay --}}
                    <form action="{{ route('stripe-source.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $plan->id }}">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M9.5,4C5.36,4 2,6.69 2,10C2,11.89 3.08,13.56 4.78,14.66L4,17L6.5,15.5C7.39,15.81 8.37,16 9.41,16C9.15,15.37 9,14.7 9,14C9,10.69 12.13,8 16,8C16.19,8 16.38,8 16.56,8.03C15.54,5.69 12.78,4 9.5,4M6.5,6.5A1,1 0 0,1 7.5,7.5A1,1 0 0,1 6.5,8.5A1,1 0 0,1 5.5,7.5A1,1 0 0,1 6.5,6.5M11.5,6.5A1,1 0 0,1 12.5,7.5A1,1 0 0,1 11.5,8.5A1,1 0 0,1 10.5,7.5A1,1 0 0,1 11.5,6.5M16,9C12.69,9 10,11.24 10,14C10,16.76 12.69,19 16,19C16.67,19 17.31,18.92 17.91,18.75L20,20L19.38,18.13C20.95,17.22 22,15.71 22,14C22,11.24 19.31,9 16,9M14,11.5A1,1 0 0,1 15,12.5A1,1 0 0,1 14,13.5A1,1 0 0,1 13,12.5A1,1 0 0,1 14,11.5M18,11.5A1,1 0 0,1 19,12.5A1,1 0 0,1 18,13.5A1,1 0 0,1 17,12.5A1,1 0 0,1 18,11.5Z"/>
                            </svg>
                            Wechat Pay
                        </button>
                    </form>

                </div>
            </x-plan-card>
        @endforeach

    @else

        {{-- Show current plan card --}}
        <x-plan-card class="mb-5" :plan="$user->subscription->plan">
            <div class="px-4 py-3 bg-gray-100 sm:px-6 text-small">
                Next payment due in {{ now()->diffInDays($user->subscription->end_date, false) }} days.
            </div>
        </x-plan-card>

        @if($user->subscription !== null && $user->subscription->status === \App\Enums\Subscription::PENDING->value)
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Cancel Subscription
                    </h3>
                    <div class="animate-pulse mt-2 sm:flex sm:items-start sm:justify-between">
                        <div class="max-w-xl text-sm text-gray-500">
                            <p>
                                You subscription is currently being processed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($user->subscription !== null && $user->subscription->status !== \App\Enums\Subscription::CANCELED->value)
            <x-card title="Cancel Subscription"
                    subtitle="Your subscription will be cancelled and will not renew.">
                <form action="{{ route('subscriptions.cancel') }}" method="post">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm font-medium rounded-md text-white text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        Cancel
                    </button>
                </form>
            </x-card>
        @else
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Cancel Subscription
                    </h3>
                    <div class="mt-2 sm:flex sm:items-start sm:justify-between">
                        <div class="max-w-xl text-sm text-gray-500">
                            <p>
                                You subscription has been cancelled. You will not be charged at the next billing cycle.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @endif
@endsection

@section('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="application/javascript">
        function createCheckoutSession(planId) {
            return fetch("{{ route('stripe.checkout') }}", {
                method: "post",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: planId
                })
            }).then((result) => {
                return result.json();
            });
        }

        function stripeCheckout(planId) {
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            createCheckoutSession(planId)
                .then(function (data) {
                    stripe.redirectToCheckout({
                        sessionId: data.sessionId
                    }).then(handleResult);
                });
        }
    </script>
@endsection
