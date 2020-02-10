@extends('layouts.app')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="list-group">
                    <div class="list-group list-group-flush">
                        <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                            <small class="text-muted">SETTINGS</small>
                        </h3>
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#profile"
                           style="cursor:pointer;">
                            <i class="fas fa-user-circle p-2" style="margin-right:10px;"></i>
                            Profile
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#security"
                           style="cursor:pointer;">
                            <i class="fas fa-lock p-2" style="margin-right:10px;"></i>
                            Security
                        </a>
                        <a class="list-group-item list-group-item-action" href="https://forums.envyclient.com"
                           style="cursor:pointer;">
                            <i class="fas fa-question-circle p-2" style="margin-right:10px;"></i>
                            Support
                        </a>
                        <br>
                        <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                            <small class="text-muted ">BILLING</small>
                        </h3>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#subscription"
                           style="cursor:pointer;">
                            <i class="fas fa-redo  p-2" style="margin-right:10px;"></i>
                            Subscription
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#credits"
                           style="cursor:pointer;">
                            <i class="fas fa-credit-card p-2" style="margin-right:10px;"></i>
                            Add Credits
                        </a>
                        @if(count($transactions) > 0)
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#invoices"
                               style="cursor:pointer;">
                                <i class="fas fa-shopping-cart p-2" style="margin-right:10px;"></i>
                                Transactions
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="tab-content">

                    <!-- profile -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="card" style="width:100%;">
                            <div class="card-header">
                                <i class="fas fa-user-circle" style="padding-right:10px;"></i> Profile
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            {!! Form::open(['action' => 'UsersController@updateCape', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                                            <div class="form-group">
                                                <h5>Cape</h5>
                                                @if($user->cape != null)
                                                    <img src="{{ url('/storage/capes/' . $user->cape) }}"
                                                         alt="cape"
                                                         class="rounded"
                                                         height="197"
                                                         width="256">
                                                @else
                                                    <img src="{{ asset('assets/default-cape.png') }}" alt="no cape"
                                                         class="rounded"
                                                         height="197"
                                                         width="256">
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                {{ Form::file('file') }}
                                            </div>
                                            {{ Form::hidden('_method', 'PUT') }}
                                            {{ Form::submit('Upload', ['class' => 'btn btn-primary']) }}
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="col">
                                            <h5>AAL Name</h5>
                                            @if($user->aal_name !== null)
                                                <input type="text" class="form-control" value="{{ $user->aal_name }}"
                                                       disabled>
                                            @else
                                                {!! Form::open(['action' => 'UsersController@updateAalName', 'method' => 'POST']) !!}
                                                {{ Form::text('name', $user->aal_name, ['class' => 'form-control', 'required', 'placeholder' => 'You can set your name only once']) }}
                                                {{ Form::hidden('_method', 'PUT') }}
                                                {{ Form::submit('Submit', ['class' => 'btn btn-secondary m-1']) }}
                                                {!! Form::close() !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- security -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-lock" style="padding-right:10px;"></i> Security
                            </div>
                            <div class="card-body">
                                <div class="container">
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
                                    {{ Form::label('password_confirmation', 'New password confrim: ') }}
                                    {{ Form::password('password_confirmation', ['class' => 'form-control', 'required']) }}
                                    <br>
                                    {{ Form::submit('Change Password', ['class' => 'btn btn-success']) }}

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="card" style="width: 100%;">
                            <ul class="list-group list-group-flush">
                                <button type="button" class="btn btn-outline-danger m-2 w-25" data-toggle="modal"
                                        data-target="#deleteModal">
                                    Delete
                                </button>
                            </ul>
                        </div>
                    </div>

                    <!-- subscriptions -->
                    <div class="tab-pane fade" id="subscription" role="tabpanel">
                        {!! Form::open(['action' => 'SubscriptionsController@subscribe', 'method' => 'POST']) !!}
                        <div class="card" style="width:100%;">
                            <div class="card-header">
                                <i class="fas fa-redo" style="padding-right:10px;"></i> Update Subscription
                            </div>
                            <ul class="list-group list-group-flush">
                                @if($user->subscription()->exists())
                                    <li class="list-group-item">
                                        You are currently subscribed to the Basic (Monthly) plan. (next payment in due
                                        in {{ $nextSubscription }} days)
                                    </li>
                                @endif
                                @foreach($plans as $plan)
                                    <li class="list-group-item">
                                        <div class="row" style="line-height:60px;">
                                            <div class="col">
                                                {{ Form::radio('id', $plan->id, $user->subscription()->exists() ? $user->subscription->plan_id === $plan->id : false, ['class' => 'form-check-inline', 'required' ,  $user->subscription()->exists() ? 'disabled' : null]) }}
                                                {{ $plan->name }}
                                            </div>
                                            <div class="col">
                                                <a class="btn btn-light">Features</a>
                                            </div>
                                            <div class="col">
                                                <b>${{ $plan->price }}</b>
                                                / {{ $plan->name === 'Lifetime' ? '∞' : "$plan->interval days" }}
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

                    <div class="tab-pane fade" id="credits" role="tabpanel">
                        <div class="card" style="width: 100%;">
                            <div class="card-header">
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
                                                ], '5', ['class' => 'form-control']) }}
                                            </div>
                                            {{ Form::submit('Add Credits', ['class' => 'btn btn-primary']) }}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if(count($transactions) > 0)
                        <div class="tab-pane fade" id="invoices" role="tabpanel">
                            <div class="card">
                                <div class="card-header"><i class="fas fa-shopping-cart"
                                                            style="padding-right:10px;"></i>
                                    Transactions
                                </div>
                                <div class="card-body">
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
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- delete modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
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
                    By delete your account you will lose access to the client & all of your current balance!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    {!! Form::open(['action' => ['UsersController@destroy', auth()->id()]]) !!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete Account', ['class' => 'btn btn-outline-danger m-sm-2']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
