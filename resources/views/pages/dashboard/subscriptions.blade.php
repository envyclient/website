@extends('layouts.dash')

@section('title', 'Subscription')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div class="alert alert-dark" style="font-size:25px;">
            <svg class="mb-1" style="width:32px;height:32px;" viewBox="0 0 24 24">
                <path fill="currentColor"
                      d="M19,8L15,12H18A6,6 0 0,1 12,18C11,18 10.03,17.75 9.2,17.3L7.74,18.76C8.97,19.54 10.43,20 12,20A8,8 0 0,0 20,12H23M6,12A6,6 0 0,1 12,6C13,6 13.97,6.25 14.8,6.7L16.26,5.24C15.03,4.46 13.57,4 12,4A8,8 0 0,0 4,12H1L5,16L9,12"/>
            </svg>
            Subscription
        </div>

        @if($user->subscription === null)
            {{-- Users subscription is being processed --}}
            @if($user->billingAgreement !== null)
                <div class="alert alert-primary" role="alert">
                    Your subscription is currently being processed.
                </div>
                <x-plan-card class="mb-3" :plan="$user->billingAgreement->plan"/>
            @else
                {{-- User has no subscription --}}
                @foreach($plans as $plan)
                    <x-plan-card class="mb-3" :plan="$plan">
                        <div class="card-footer d-flex justify-content-end">

                            {{-- Subscribe using PayPal --}}
                            <form action="{{ route('paypal.process') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $plan->id }}">
                                <button type="submit" class="btn a1 m-1 rounded-3"
                                        style="background: #0079C1;color: white;">
                                    <svg style="width:24px;height:24px" viewBox="0 0 1792 1792">
                                        <path fill="currentColor"
                                              d="M1647 646q18 84-4 204-87 444-565 444h-44q-25 0-44 16.5t-24 42.5l-4 19-55 346-2 15q-5 26-24.5 42.5t-44.5 16.5h-251q-21 0-33-15t-9-36q9-56 26.5-168t26.5-168 27-167.5 27-167.5q5-37 43-37h131q133 2 236-21 175-39 287-144 102-95 155-246 24-70 35-133 1-6 2.5-7.5t3.5-1 6 3.5q79 59 98 162zm-172-282q0 107-46 236-80 233-302 315-113 40-252 42 0 1-90 1l-90-1q-100 0-118 96-2 8-85 530-1 10-12 10h-295q-22 0-36.5-16.5t-11.5-38.5l232-1471q5-29 27.5-48t51.5-19h598q34 0 97.5 13t111.5 32q107 41 163.5 123t56.5 196z"/>
                                    </svg>
                                    PayPal
                                </button>
                            </form>

                            {{-- Subscribe using Credit/Debit Card --}}
                            <button type="button" class="btn a1 btn-dark m-1 rounded-3"
                                    onclick="stripeCheckout({{ $plan->id }})">
                                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                          d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/>
                                </svg>
                                Credit Card
                            </button>

                            {{-- Purchase using Wechat Pay --}}
                            <form action="{{ route('stripe-source.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $plan->id }}">
                                <button type="submit" class="btn a1 m-1 rounded-3"
                                        style="background: #61b15a;color: white;">
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                              d="M9.5,4C5.36,4 2,6.69 2,10C2,11.89 3.08,13.56 4.78,14.66L4,17L6.5,15.5C7.39,15.81 8.37,16 9.41,16C9.15,15.37 9,14.7 9,14C9,10.69 12.13,8 16,8C16.19,8 16.38,8 16.56,8.03C15.54,5.69 12.78,4 9.5,4M6.5,6.5A1,1 0 0,1 7.5,7.5A1,1 0 0,1 6.5,8.5A1,1 0 0,1 5.5,7.5A1,1 0 0,1 6.5,6.5M11.5,6.5A1,1 0 0,1 12.5,7.5A1,1 0 0,1 11.5,8.5A1,1 0 0,1 10.5,7.5A1,1 0 0,1 11.5,6.5M16,9C12.69,9 10,11.24 10,14C10,16.76 12.69,19 16,19C16.67,19 17.31,18.92 17.91,18.75L20,20L19.38,18.13C20.95,17.22 22,15.71 22,14C22,11.24 19.31,9 16,9M14,11.5A1,1 0 0,1 15,12.5A1,1 0 0,1 14,13.5A1,1 0 0,1 13,12.5A1,1 0 0,1 14,11.5M18,11.5A1,1 0 0,1 19,12.5A1,1 0 0,1 18,13.5A1,1 0 0,1 17,12.5A1,1 0 0,1 18,11.5Z"/>
                                    </svg>
                                    Wechat Pay
                                </button>
                            </form>

                        </div>
                    </x-plan-card>
                @endforeach
            @endif
        @else
            <x-plan-card class="mb-3" :plan="$user->subscription->plan">
                <div class="card-footer">
                    Next payment due in {{ $end }} days.
                </div>
            </x-plan-card>

            @if($user->billingAgreement?->state === 'Cancelled' || $user->subscription?->stripe_status === 'Cancelled')
                <div class="alert alert-danger" role="alert">
                    You subscription has been cancelled. You will not be charged at the next billing cycle.
                </div>
            @elseif($user->subscription !== null)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-normal">
                            Your subscription will be cancelled and will not renew at the end of billing period.
                        </h5>
                        <form action="{{ route('subscriptions.cancel') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger m-1 rounded-3">
                                Cancel Subscription
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>
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
            const stripe = Stripe("{{ config('stripe.key') }}");
            createCheckoutSession(planId)
                .then(function (data) {
                    stripe.redirectToCheckout({
                        sessionId: data.sessionId
                    }).then(handleResult);
                });
        }
    </script>
@endsection
