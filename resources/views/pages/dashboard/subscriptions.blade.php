@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div class="alert alert-secondary" style="font-size:25px;">
            <i class="fab fa-cc-paypal" style="padding-right:10px;"></i> Subscription
        </div>

        <form method="post" action="{{ route('paypal.process') }}">
            @csrf
            <div class="card" style="width:100%;">

                @if($user->hasSubscription())
                    <div class="card-header">
                        You are currently subscribed to the
                        <strong>{{ $user->subscription->plan->name }}</strong>
                        plan. (next payment due in {{ $nextSubscription }} days)
                    </div>
                @endif

                <ul class="list-group list-group-flush">
                    @foreach($plans as $plan)
                        <li class="list-group-item">
                            <div class="row" style="line-height:60px;">

                                <div class="col">
                                    <input class="form-check-input"
                                           style="margin-top: 22px;"
                                           id="id"
                                           name="id"
                                           type="radio"
                                           value="{{ $plan->id }}"
                                           {{ $user->hasSubscription() ? 'disabled' : null }}
                                           {{  $user->hasSubscription() && $user->subscription->plan_id === $plan->id ? 'checked' : null }}
                                           required>
                                    <label class="form-check-label" for="id">
                                        {{ $plan->name }}
                                    </label>
                                </div>

                                <div class="col">
                                    <button type="button"
                                            class="btn btn-light"
                                            data-bs-toggle="modal"
                                            data-bs-target="#plans-modal"
                                            data-bs-title="{{ $plan->name }} Plan"
                                            data-bs-configs="{{ $plan->config_limit }}"
                                            data-bs-beta="{{ $plan->beta_access }}"
                                            data-bs-capes="{{ $plan->capes_access }}"
                                    >
                                        Features
                                    </button>
                                </div>

                                <div class="col">
                                    <b>${{ $plan->price }}</b> / 30 days
                                </div>

                                <div class="col">
                                    <svg style="width:32px;height:32px" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                              d="M3,12V6.75L9,5.43V11.91L3,12M20,3V11.75L10,11.9V5.21L20,3M3,13L9,13.09V19.9L3,18.75V13M20,13.25V22L10,20.09V13.1L20,13.25Z"/>
                                    </svg>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="card-footer text-muted">
                    It can take up to 10 minutes to process/cancel your subscription.
                </div>
            </div>

            <br>

            @if(!$user->hasSubscription() && !$user->hasBillingAgreement())
                <div class="d-grid gap-2">
                    <input class="btn btn-outline-success btn-lg " type="submit" value="Subscribe">
                </div>
            @endif
        </form>

        @if(!$user->hasSubscription() && !$user->hasBillingAgreement() && $user->access_free_plan)
            <form method="post" action="{{ route('subscriptions.free') }}" class="mt-2">
                @csrf
                <div class="d-grid gap-2">
                    <input class="btn btn-outline-secondary btn-lg" type="submit"
                           value="Subscribe to free plan">
                </div>
            </form>
        @endif

        @if($user->hasSubscription())
            <form method="post" action="{{ route('subscriptions.cancel') }}">
                @csrf
                <div class="d-grid gap-2">
                    <input class="btn btn-outline-danger btn-lg" type="submit" value="Cancel Subscription">
                </div>
            </form>
        @elseif($user->hasBillingAgreement() && $user->billingAgreement->state === 'Canceled')
            <div class="card" style="width: 100%;">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg" disabled>
                        Subscription Cancelled
                    </button>
                </div>
            </div>
        @elseif($user->hasBillingAgreement())
            <div class="card" style="width: 100%;">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg" disabled>
                        Processing Subscription...
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="plans-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="application/javascript">
        const plansModal = document.getElementById('plans-modal');
        plansModal.addEventListener('show.bs.modal', function (event) {

            // Button that triggered the modal
            const button = event.relatedTarget;

            const modalTitle = plansModal.querySelector('.modal-title');
            const modalBody = plansModal.querySelector('.modal-body');

            // data
            const content = [];
            const title = button.getAttribute('data-bs-title');
            const configCount = button.getAttribute('data-bs-configs');
            const betaAccess = button.getAttribute('data-bs-beta');
            const capesAccess = button.getAttribute('data-bs-capes');

            content.push(`<p>&#10003; ${configCount} Configs</p>`);

            if (betaAccess) {
                content.push(`<p>&#10003; Beta Access</p>`);
            } else {
                content.push(`<p>&#120; Beta Access</p>`);
            }

            if (capesAccess) {
                content.push(`<p>&#10003; Capes Access</p>`);
            } else {
                content.push(`<p>&#120; Capes Access</p>`);
            }

            modalTitle.textContent = title;
            modalBody.innerHTML = content.join("");
        });
    </script>
@endsection
