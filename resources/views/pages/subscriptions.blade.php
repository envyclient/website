@extends('layouts.dash')

@section('content')
    <div style="width:95%;margin:0 auto">
        <div class="alert alert-secondary" style="font-size:25px;">
            <i class="fas fa-redo" style="padding-right:10px;"></i> Subscription
        </div>
        <form method="POST" action="{{ route('paypal.process') }}" accept-charset="UTF-8">
            @csrf
            <div class="card" style="width:100%;">
                @if($user->hasSubscription())
                    <div class="card-header">
                        You are currently subscribed to the {{ $user->subscription->plan->name  }} plan.
                        (next payment due in {{ $nextSubscription }} days)
                    </div>
                @endif
                <ul class="list-group list-group-flush">
                    @foreach($plans as $plan)
                        <li class="list-group-item">
                            <div class="row" style="line-height:60px;">
                                <div class="col">
                                    <input class="form-check-inline" required name="id" type="radio"
                                           value="{{ $plan->id }}"
                                        {{ $user->hasSubscription() ? 'disabled' : null }}
                                        {{  $user->hasSubscription() && $user->subscription->plan_id === $plan->id? 'checked' : null }}>
                                    <label class="form-check-label" for="exampleRadios1">
                                        {{ $plan->name }}
                                    </label>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-light" data-toggle="modal"
                                            data-target="#{{ $plan->name }}-modal">
                                        Features
                                    </button>
                                </div>
                                <div class="col">
                                    <b>${{ $plan->price }}</b> / 30 days
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
            @if($user->hasSubscription())
        </form> <!-- close form -->
        <div class="card" style="width: 100%;">
            @if($user->subscribedToFreePlan())
                <h5 class="text-muted pt-2 pl-2">
                    You can not cancel your subscription because you are subscribed to the free plan.
                </h5>
            @else
                <form method="POST" action="{{ route('subscriptions.cancel') }}" accept-charset="UTF-8">
                    <input class="btn btn-outline-danger btn-lg btn-block" type="submit"
                           value="Cancel Subscription">
                </form>
            @endif
        </div>
        @elseif($user->hasBillingAgreement())
        </form> <!-- close form -->
        <div class="card" style="width: 100%;">
            <button type="button" class="btn btn-lg btn-primary btn-block" disabled>
                Subscription in progress.
            </button>
        </div>
        @else
            <input class="btn btn-outline-success btn-lg btn-block" type="submit"
                   value="Subscribe">
            </form> <!-- close form -->
            @if($user->access_free_plan)
                <form method="POST" action="{{ route('subscriptions.free') }}" class="mt-2">
                    @csrf
                    <input class="btn btn-outline-secondary btn-lg btn-block" type="submit"
                           value="Subscribe to free plan">
                </form>
            @endif
        @endif
    </div>

    <!-- TODO: use js to set dynamic data -->
    @foreach($plans as $plan)
        <div class="modal fade" id="{{ $plan->name }}-modal" tabindex="-1" role="dialog"
             aria-labelledby="{{ $plan->name }}-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="{{ $plan->name }}-label">Features - {{ $plan->name }} Plan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $plan->name }}</p>
                        <p>{{ $plan->config_limit }} Configs</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
