<!--- Subscriptions Section --->
<div class="tab-pane fade" id="subscription" role="tabpanel">
    <div class="alert alert-secondary" style="font-size:25px;">
        <i class="fas fa-redo" style="padding-right:10px;"></i> Subscription
    </div>
    {!! Form::open(['action' => 'PayPalController@processAgreement', 'method' => 'POST']) !!}
    <div class="card" style="width:100%;">
        <ul class="list-group list-group-flush">
            @if($user->hasSubscription())
                <li class="list-group-item">
                    You are currently subscribed to the {{ $user->subscription->plan->name  }} plan.
                    (next payment due in {{ $nextSubscription }} days)
                </li>
            @endif
            @foreach($plans as $plan)
                <li class="list-group-item">
                    <div class="row" style="line-height:60px;">
                        <div class="col">
                            {{ Form::radio('id', $plan->id, $user->hasSubscription() ? $user->subscription->plan_id === $plan->id : false, ['class' => 'form-check-inline', 'required' ,  $user->hasSubscription() ? 'disabled' : null]) }}
                            {{ $plan->name }}
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
    </div>
    <br>
    @if($user->hasSubscription())
        <div class="card" style="width: 100%;">
            <ul class="list-group list-group-flush">
                {!! Form::close() !!}
                {!! Form::open(['action' => 'CancelSubscription', 'method' => 'POST']) !!}
                {{ Form::submit('Cancel Subscription', ['class' => 'btn btn-outline-danger btn-lg btn-block']) }}
                {!! Form::close() !!}
            </ul>
        </div>
    @elseif($user->hasBillingAgreement())
        <div class="card" style="width: 100%;">
            <ul class="list-group list-group-flush">
                {!! Form::close() !!}
                <button type="button" class="btn btn-lg btn-primary btn-block" disabled>
                    Subscription in progress.
                </button>
            </ul>
        </div>
    @else
        <div class="card" style="width: 100%;">
            <ul class="list-group list-group-flush">
                {{ Form::submit('Subscribe', ['class' => 'btn btn-outline-success btn-lg btn-block']) }}
                {!! Form::close() !!}
            </ul>
        </div>
    @endif
</div>
