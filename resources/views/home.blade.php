@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {!! Form::open(['action' => 'PayPalController@create', 'method' => 'POST']) !!}
                        {{ Form::number('price', 10, ['min' => 5, 'max' => 100]) }}
                        {{ Form::submit('Add', ['class' => 'btn btn-secondary', 'style' => 'margin-right: 5px']) }}
                        {!! Form::close() !!}

                        <hr>

                        <h5>Subscribe</h5>
                        @foreach($plans as $plan)
                            {!! Form::open(['action' => 'SubscriptionsController@subscribe', 'method' => 'POST']) !!}
                            {{ Form::hidden('plan', $plan->id) }}
                            {{ Form::submit('' . $plan->price . ' / '.$plan->title, ['class' => 'btn btn-secondary']) }}
                            {!! Form::close() !!}
                        @endforeach
                        {!! Form::open(['action' => 'SubscriptionsController@cancel', 'method' => 'POST']) !!}
                        {{ Form::submit('Cancel', ['class' => 'btn btn-danger']) }}
                        {!! Form::close() !!}

                        <hr>

                        <h5>AAL Name (you can not change)</h5>
                        @if($user->aal_name !== null)
                            <input type="text" class="form-control" value="{{ $user->aal_name }}" disabled>
                        @else
                            {!! Form::open(['action' => 'UsersController@updateAalName', 'method' => 'POST']) !!}
                            {{ Form::text('name', $user->aal_name, ['class' => 'form-control', 'required']) }}
                            {{ Form::hidden('_method', 'PUT') }}
                            {{ Form::submit('Submit', ['class' => 'btn btn-secondary']) }}
                            {!! Form::close() !!}
                        @endif

                        <hr>

                        <div>
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
                                    <img src="{{ asset('assets/default-cape.png') }}" alt="no cape" class="rounded"
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


                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Transactions</div>
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
        </div>
    </div>
@endsection
