@extends('layouts.dash')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="tab-content">

                    <!-- stats -->
                    <div class="tab-pane fade show active" id="statistics" role="tabpanel">
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-dollar-sign" style="padding-right:10px;"></i> Stats
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card bg-light mb-3 mb-3" style="max-width: 18rem;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center" style="font-size: 25px;">
                                                        ${{ $stats['moneyToday'] }}</h5>
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
                                                        ${{ $stats['moneyWeek'] }}</h5>
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
                                                        ${{ $stats['moneyMonth'] }}</h5>
                                                </div>
                                                <div
                                                    class="card-footer bg-transparent text-center font-weight-bold">
                                                    MONTH
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(count($stats['todayTransactions']) > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">User</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($stats['todayTransactions'] as $transaction)
                                                <tr>
                                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                                    <td>{{ $transaction->wallet->user->name }}</td>
                                                    <td style="color: green">${{ $transaction->amount }}</td>
                                                    <td>{{ $transaction->meta['description'] }}</td>
                                                    <td>{{ $transaction->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- users table -->
                    <div class="tab-pane fade" id="users" role="tabpanel">
                        <div class="card-body">

                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-users" style="padding-right:10px;"></i>Stats
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card bg-light mb-3 mb-3" style="max-width: 18rem;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center" style="font-size: 25px;">
                                                        {{ $userStats['today'] }}
                                                    </h5>
                                                </div>
                                                <div
                                                    class="card-footer bg-transparent text-center font-weight-bold">
                                                    USERS<br>TODAY
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card bg-light mb-3 mb-3" style="max-width: 18rem;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center" style="font-size: 25px;">
                                                        {{ $userStats['month'] }}
                                                    </h5>
                                                </div>
                                                <div
                                                    class="card-footer bg-transparent text-center font-weight-bold">
                                                    TOTAL<br>MONTH
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card bg-light mb-3" style="max-width: 18rem;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center" style="font-size: 25px;">
                                                        {{ $userStats['subscriptions'] }}</h5>
                                                </div>
                                                <div
                                                    class="card-footer bg-transparent text-center font-weight-bold">
                                                    PAID<br>USERS
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card bg-light mb-3" style="max-width: 18rem;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center" style="font-size: 25px;">
                                                        {{ $userStats['total'] }}</h5>
                                                </div>
                                                <div
                                                    class="card-footer bg-transparent text-center font-weight-bold">
                                                    TOTAL<br>USERS
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="card">

                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <i class="fas fa-users-cog" style="padding-right:10px;"></i> Users
                                        </div>
                                        <div class="col">
                                            <input type="text" id="search"
                                                   class="form-control form-control-sm ml-3 w-75 d-inline-block float-right"
                                                   placeholder="Search">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach($users as $user)
                                        {!! Form::open(['action' => ['UsersController@addCredits', $user], 'method' => 'POST', 'id' => "f-$user->name"]) !!}
                                        {!! Form::close() !!}
                                    @endforeach
                                    @foreach($users as $user)
                                        {!! Form::open(['action' => ['UsersController@ban', $user], 'method' => 'POST', 'id' => "f-ban-$user->name"]) !!}
                                        {!! Form::close() !!}
                                    @endforeach
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Balance</th>
                                            <th scope="col">Subscription</th>
                                            <th scope="col">Expiration</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="users-table">
                                        @foreach($users as $user)
                                            <tr>
                                                <th scope="row">{{ $loop->index + 1 }}</th>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>${{ $user->balance == null ? 0 :  $user->balance }}</td>
                                                <td>{{ $user->hasSubscription() ? $user->subscription->plan->name : null }}</td>
                                                <td>{{ $user->hasSubscription() ? $user->subscription->end_date->diffInDays() . ' days' : null }}</td>
                                                <td>
                                                    <button type="button"
                                                            class="btn btn-success d-inline-block text-white"
                                                            data-toggle="modal"
                                                            data-target="#modal-{{ $user->name }}">
                                                        <i class="fas fa-coins"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-danger d-inline-block text-white"
                                                            data-toggle="modal"
                                                            data-target="#modal-ban-{{ $user->name }}">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modal-{{ $user->name }}" tabindex="-1"
                                                 role="dialog" aria-labelledby=""
                                                 aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Add
                                                                coins to {{$user->name}}'s account.</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    {{ Form::hidden('_method', 'PUT', ['form' => "f-$user->name"]) }}
                                                                    {{ Form::label('amount', 'Cash amount: ', ['form' => "f-$user->name"]) }}
                                                                    {{ Form::select('amount', [
                                                                        '5' => '$5',
                                                                        '10' => '$10',
                                                                        '15' => '$15',
                                                                        '20' => '$20',
                                                                        '30' => '$30',
                                                                        '40' => '$40',
                                                                        '50' => '$50',
                                                                    ], '5', ['class' => 'form-control', 'form' => "f-$user->name"]) }}
                                                                </div>
                                                                {{ Form::submit('Add Credits', ['class' => 'btn btn-primary', 'form' => "f-$user->name", 'method' => 'PUT']) }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modal-ban-{{ $user->name }}" tabindex="-1"
                                                 role="dialog" aria-labelledby=""
                                                 aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Ban
                                                                Interface</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    {{ Form::hidden('_method', 'PUT', ['form' => "f-ban-$user->name"]) }}
                                                                    {{ Form::label('reason', 'Please specify the ban reason: ', ['form' => "f-ban-$user->name"]) }}
                                                                    {{ Form::select('reason', [
                                                                        'Chargeback' => 'Chargeback',
                                                                        'Abuse of power' => 'Abuse of power',
                                                                        'Exploiting' => 'Exploiting',
                                                                        'Account Sharing' => 'Account Sharing',
                                                                        'Blacklisted' => 'Blacklisted',
                                                                    ], 'Chargeback', ['class' => 'form-control', 'form' => "f-ban-$user->name"]) }}
                                                                </div>
                                                                {{ Form::submit('Ban', ['class' => 'btn btn-danger', 'form' => "f-ban-$user->name", 'method' => 'PUT']) }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $("#search").on("keyup", function () {
                const value = $(this).val().toLowerCase();
                $("#users-table tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endsection
