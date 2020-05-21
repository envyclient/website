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
                @if($user->hasSubscription())
                    <div class="alert alert-secondary" style="font-size:25px;">
                        <i class="fas fa-file" style="padding-right:10px;"></i>Configs
                    </div>
                    <div class="text-left">
                        <label>Configs Used:</label>
                        <h5>
                            <span class="badge badge-secondary">{{ $configs->count() }}/<span
                                    class="font-weight-bold">{{ $user->getConfigLimit() }}</span>
                            </span>
                        </h5>
                        @if(count($configs) > 0)
                            <div class="table-responsive table-sticky" style="overflow-y: scroll;max-height: 400px;">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">name</th>
                                        <th scope="col">public</th>
                                        <th scope="col">favorites</th>
                                        <th scope="col">created</th>
                                        <th scope="col">last updated</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($configs as $config)
                                        <tr>
                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                            <td>{{ $config->name }}</td>
                                            <td>{{ $config->public ? 'true' : 'false' }}</td>
                                            <td>{{ $config->favorites_count }}</td>
                                            <td>{{ $config->created_at->diffForHumans() }}</td>
                                            <td>{{ $config->updated_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <br>
                    <a class="btn btn-primary btn-lg btn-block" href="{{ route('versions.launcher') }}">
                        <i class="fas fa-download pr-1"></i> Download Launcher
                    </a>
                @endif
            </div>
        </div>

        <!--- Security Section --->
        <div class="tab-pane fade" id="security" role="tabpanel">
            <div class="alert alert-secondary" style="font-size:25px;">
                <i class="fas fa-lock" style="padding-right:10px;"></i> Security
            </div>
            <div class="card card-body">
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
            <br>
            <div class="card" style="width: 100%;">
                <ul class="list-group list-group-flush">
                    <button type="button" class="btn btn-outline-danger btn-lg btn-block" data-toggle="modal"
                            data-target="#disableAccountModal">
                        Disable Account
                    </button>
                </ul>
            </div>
        </div>

        <!--- Subscriptions Section --->
        <div class="tab-pane fade" id="subscription" role="tabpanel">
            <div class="alert alert-secondary" style="font-size:25px;">
                <i class="fas fa-redo" style="padding-right:10px;"></i> Subscription
            </div>
            {!! Form::open(['action' => 'SubscriptionsController@subscribe', 'method' => 'POST']) !!}
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
                        {{ Form::submit('Cancel Subscription', ['class' => 'btn btn-outline-danger btn-lg btn-block']) }}
                        {!! Form::close() !!}
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

        <!-- Add Credits Section -->
        <div class="tab-pane fade" id="credits" role="tabpanel">
            <div class="alert alert-secondary" style="font-size:25px;">
                <i class="fas fa-credit-card" style="padding-right:10px;"></i> Add Credits
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5 class="font-weight-bold">Payment methods</h5>
                    <h6>Currently we are only supporting PayPal payments</h6>
                    <div class="container m-3 font-weight-bold">
                        <p>Terms and Conditions</p>
                        <ul class="font-weight-normal">
                            <li>
                                By adding credits you are agreeing to our
                                <a href="{{ route('terms') }}">Terms and Conditions.</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card" style="width: 100%;margin-top:10px;">
                        <div class="card-body">
                            <h5 class="card-title" style="font-weight: bold;">
                                <i class="fab fa-paypal"></i> PayPal
                            </h5>
                            {!! Form::open(['action' => 'PayPalController@create']) !!}
                            <div class="form-group">
                                {{ Form::label('amount', 'Amount:') }}
                                <br>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    @foreach([5, 10, 15, 20, 30] as $amount)
                                        @if($loop->first)
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="amount" autocomplete="off"
                                                       value="{{ $amount }}" checked>
                                                ${{ $amount }}
                                            </label>
                                        @else
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="amount" autocomplete="off"
                                                       value="{{ $amount }}">
                                                ${{ $amount }}
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            {{ Form::submit('Add Credits', ['class' => 'btn btn-primary']) }}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Transactions Section -->
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

        <!-- disable account modal -->
        <div class="modal fade" id="disableAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Wooooow! Are you sure there bud?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                        </button>
                    </div>
                    <div class="modal-body" style="color:red;">
                        By disabling your account you will lose access to your account and if you have a subscription it
                        will continue till the end date.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        {!! Form::open(['action' => 'UsersController@disable', 'method' => 'DELETE']) !!}
                        {{ Form::submit('Disable Account', ['class' => 'btn btn-outline-danger m-sm-2']) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
@endsection
