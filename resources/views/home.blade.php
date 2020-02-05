@extends('layouts.app')

@section('content')
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
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#support"
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
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#invoices"
                           style="cursor:pointer;">
                            <i class="fas fa-shopping-cart p-2" style="margin-right:10px;"></i>
                            Transactions
                        </a>
                        @if ($user->admin)
                            <br>
                            <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                                <small class="text-muted ">ADMINISTRATOR</small>
                            </h3>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#statistics"
                               style="cursor:pointer;">
                                <i class="fas fa-balance-scale p-2" style="margin-right:10px;"></i>
                                Statistics
                            </a>
                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#invoices"
                               style="cursor:pointer;">
                                <i class="fas fa-users-cog p-2" style="margin-right:10px;"></i>
                                Users
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="tab-content">
                    <div class="tab-pane fade" id="profile" role="tabpanel">
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
                                            <h5>AAL Name (Can only be set once)</h5>
                                            @if($user->aal_name !== null)
                                                <input type="text" class="form-control" value="{{ $user->aal_name }}"
                                                       disabled>
                                            @else
                                                {!! Form::open(['action' => 'UsersController@updateAalName', 'method' => 'POST']) !!}
                                                {{ Form::text('name', $user->aal_name, ['class' => 'form-control', 'required']) }}
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
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-lock" style="padding-right:10px;"></i> Security
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <h6>Name: </h6>
                                    <input type="text" class="form-control" value="{{$user->name}}" disabled>
                                    <br>
                                    <h6>Email: </h6>
                                    <input type="text" class="form-control" value="{{$user->email}}" disabled>
                                    <br>
                                    <h6>Password: </h6>
                                    <input type="password" class="form-control" value="">
                                    <br>
                                    <h6>Confirm Password: </h6>
                                    <input type="password" class="form-control" value="">
                                    <br>
                                    <button type="button" class="btn btn-success">Change Password</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="card" style="width: 100%;">
                            <ul class="list-group list-group-flush">
                                <button type="button" class="btn btn-outline-danger w-25 m-sm-2">Delete Account</button>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="support" role="tabpanel">
                        <div class="card" style="width:100%;">
                            <div class="card-header">
                                <i class="fas fa-question-circle" style="padding-right:10px;"></i> Support
                            </div>
                            <ul class="card-body">
                                <li class="list-group-item">Working progress...</li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="subscription" role="tabpanel">
                        <div class="card" style="width:100%;">
                            <div class="card-header">
                                <i class="fas fa-redo" style="padding-right:10px;"></i> Update Subscription
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">You are currently subscribed to the Basic (Monthly) plan.
                                </li>
                                <li class="list-group-item">
                                    <div class="row" style="line-height:60px;">
                                        <div class="col">
                                            <input class="form-check-inline" type="radio" name="exampleRadios"
                                                   id="exampleRadios1" value="option1"> Free
                                        </div>
                                        <div class="col">
                                            <a class="btn btn-light">Features</a>
                                        </div>
                                        <div class="col">
                                            Free
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row" style="line-height:60px;">
                                        <div class="col">
                                            <input class="form-check-inline" type="radio" name="exampleRadios"
                                                   id="exampleRadios1" value="option2" checked> Basic
                                        </div>
                                        <div class="col">
                                            <a class="btn btn-light">Features</a>
                                        </div>
                                        <div class="col">
                                            $10.00 / Monthly
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <br>
                        <div class="card" style="width: 100%;">
                            <ul class="list-group list-group-flush">
                                <button type="button" class="btn btn-outline-danger w-25 m-sm-2">Cancel Subscription
                                </button>
                            </ul>
                        </div>
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
                                            <div class="form-group">
                                                <label for="sel1">Cash amount: </label>
                                                <select class="form-control" id="sel1">
                                                    <option>$5</option>
                                                    <option>$10</option>
                                                    <option>$15</option>
                                                    <option>$20</option>
                                                </select>
                                            </div>
                                            <button type="button" class="btn btn-primary">Add Credits</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="invoices" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><i class="fas fa-shopping-cart" style="padding-right:10px;"></i>
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
                    @if($user->admin)
                        <div class="tab-pane fade show active" id="statistics" role="tabpanel">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fas fa-dollar-sign" style="padding-right:10px;"></i> Profit
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card bg-light mb-3 mb-3" style="max-width: 18rem;">
                                                    <div class="card-body">
                                                        <h5 class="card-title text-center" style="font-size: 25px;">
                                                            $10</h5>
                                                    </div>
                                                    <div
                                                        class="card-footer bg-transparent text-center font-weight-bold">
                                                        TODAY
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light mb-3 mb-3" style="max-width: 18rem;">
                                                    <div class="card-body">
                                                        <h5 class="card-title text-center" style="font-size: 25px;">
                                                            $50</h5>
                                                    </div>
                                                    <div
                                                        class="card-footer bg-transparent text-center font-weight-bold">
                                                        WEEK
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light mb-3" style="max-width: 18rem;">
                                                    <div class="card-body">
                                                        <h5 class="card-title text-center" style="font-size: 25px;">
                                                            $150</h5>
                                                    </div>
                                                    <div
                                                        class="card-footer bg-transparent text-center font-weight-bold">
                                                        MONTH
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fas fa-rocket" style="padding-right:10px;"></i> Client Launches
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card bg-light mb-3 mb-3" style="max-width: 18rem;">
                                                    <div class="card-body">
                                                        <h5 class="card-title text-center" style="font-size: 25px;">
                                                            50</h5>
                                                    </div>
                                                    <div
                                                        class="card-footer bg-transparent text-center font-weight-bold">
                                                        TODAY
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light mb-3 mb-3" style="max-width: 18rem;">
                                                    <div class="card-body">
                                                        <h5 class="card-title text-center" style="font-size: 25px;">
                                                            250</h5>
                                                    </div>
                                                    <div
                                                        class="card-footer bg-transparent text-center font-weight-bold">
                                                        WEEK
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card bg-light mb-3" style="max-width: 18rem;">
                                                    <div class="card-body">
                                                        <h5 class="card-title text-center" style="font-size: 25px;">
                                                            500</h5>
                                                    </div>
                                                    <div
                                                        class="card-footer bg-transparent text-center font-weight-bold">
                                                        MONTH
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
