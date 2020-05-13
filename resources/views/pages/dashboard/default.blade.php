@extends('layouts.dash')

@section('content')
    <div class="tab-content" style="width:95%;margin:0 auto">

        <!--- Profile Section --->
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <div>
                <div class="alert alert-secondary" style="font-size:25px;">
                    <i class="fas fa-user" style="padding-right:10px;"></i> Profile
                </div>

                <div class="text-left">
                    <label>Member Since:</label>
                    <input class="form-control" placeholder="Date" disabled
                           value="{{ $user->created_at->format('Y-m-d') }}">
                </div>
            </div>
            <br>
            <div>
                <div class="alert alert-secondary" style="font-size:25px;">
                    <i class="fas fa-file" style="padding-right:10px;"></i>Configs
                </div>

                @if($user->hasSubscription())
                    <div class="text-left">
                        <label>Configs Used:</label>
                        <h5>
                            <span class="badge badge-secondary">{{ $configs->count() }}/<span
                                    class="font-weight-bold">{{ $user->getConfigLimit() }}</span>
                            </span>
                        </h5>
                        @if($configs->count() > 0)
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>name</th>
                                    <th>favorites</th>
                                    <th>date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($configs as $config)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                        <td>{{ $config->name }}</td>
                                        <td>{{ $config->favorites()->count() }}</td>
                                        <td>{{ $config->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!--- Security Section --->
        <div class="tab-pane fade" id="security" role="tabpanel">
            <div class="alert alert-secondary" style="font-size:25px;">
                <i class="fas fa-lock" style="padding-right:10px;"></i> Security
            </div>
            {!! Form::open(['action' => 'UsersController@updatePassword']) !!}
            {{ Form::hidden('_method', 'PUT') }}

            {{ Form::label('name', 'Name: ') }}
            {{ Form::text('name', $user->name, ['class' => 'form-control', 'disabled']) }}
            <br>
            {{ Form::label('email', 'Email: ') }}
            {{ Form::text('email', $user->email, ['class' => 'form-control', 'disabled']) }}
            <br>
            {{ Form::label('password_current', 'Current password: ') }}
            {{ Form::password('password_current', ['class' => 'form-control', 'required']) }}
            <br>
            {{ Form::label('password', 'New password: ') }}
            {{ Form::password('password', ['class' => 'form-control', 'required']) }}
            <br>
            {{ Form::label('password_confirmation', 'New password confirm: ') }}
            {{ Form::password('password_confirmation', ['class' => 'form-control', 'required']) }}
            <br>
            {{ Form::submit('Change Password', ['class' => 'btn btn-success']) }}

            {!! Form::close() !!}
        </div>

        <!--- Subscriptions Section --->
        <div class="tab-pane fade" id="subscription" role="tabpanel">
            <div class="alert alert-secondary" style="font-size:25px;">
                <i class="fas fa-redo" style="padding-right:10px;"></i> Update Subscription
            </div>
            {!! Form::open(['action' => 'SubscriptionsController@subscribe', 'method' => 'POST']) !!}
            <div class="card" style="width:100%;">
                <ul class="list-group list-group-flush">
                    @if($user->hasSubscription())
                        <li class="list-group-item">
                            You are currently subscribed to the {{ $user->subscription->plan->name  }} plan.
                            (next payment in due
                            in {{ $nextSubscription }}
                            days)
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
                                    <b>${{ $plan->price }}</b>
                                    / {{ "$plan->interval days" }}
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
                        {!! Form::open(['action' => 'SubscriptionsController@cancel', 'method' => 'POST']) !!}
                        {{ Form::submit('Cancel Subscription', ['class' => 'btn btn-outline-danger w-25 m-sm-2']) }}
                        {!! Form::close() !!}
                    </ul>
                </div>
            @else
                <div class="card" style="width: 100%;">
                    <ul class="list-group list-group-flush">
                        {{ Form::submit('Subscribe', ['class' => 'btn btn-outline-success m-2 w-25']) }}
                        {!! Form::close() !!}
                    </ul>
                </div>
            @endif
        </div>

        <!-- add payment -->
        <div class="tab-pane fade" id="credits" role="tabpanel">
            <div class="alert alert-secondary" style="font-size:25px;">
                <i class="fas fa-credit-card" style="padding-right:10px;"></i> Add Credits
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5 class="font-weight-bold">Payment methods</h5>
                    <h7>Currently we are only supporting PayPal payments</h7>
                    <div class="container m-3 font-weight-bold">
                        <p>Terms and Conditions</p>
                        <ul class="font-weight-normal">
                            <li>Charging back is not allowed and if detected your account will be
                                terminated.
                            </li>
                        </ul>
                    </div>
                    <div class="card" style="width: 100%;margin-top:10px;">
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight: bold;"><i
                                    class="fab fa-paypal"></i> PayPal</h5>
                            <p class="card-text">By purchasing credits you agree to all the terms
                                above.</p>
                            {!! Form::open(['action' => 'PayPalController@create']) !!}
                            <div class="form-group">
                                {{ Form::label('amount', 'Cash amount: ') }}
                                {{ Form::select('amount', [
                                    '1' => '$1',
                                    '5' => '$5',
                                    '7' => '$7',
                                    '10' => '$10',
                                    '15' => '$15',
                                    '20' => '$20',
                                    '25' => '$25',
                                    '30' => '$30',
                                    '35' => '$35',
                                    '40' => '$40',
                                    '50' => '$50',
                                    '60' => '$60',
                                    '70' => '$70',
                                    '80' => '$80',
                                ], '7', ['class' => 'form-control']) }}
                            </div>
                            {{ Form::submit('Add Credits', ['class' => 'btn btn-primary']) }}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- transactions -->
        <div class="tab-pane fade" id="invoices" role="tabpanel">
            <div class="alert alert-secondary" style="font-size:25px;">
                <i class="fas fa-shopping-cart" style="padding-right:10px;"></i> Transactions
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <th scope="row">{{ $loop->index + 1 }}</th>
                        @if($transaction->type == 'deposit')
                            <td style="color: green">+{{ $transaction->amount }}</td>
                        @else
                            <td style="color: red">-{{ $transaction->amount }}</td>
                        @endif
                        <td>{{ $transaction->meta['description'] }}</td>
                        <td>{{ $transaction->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
