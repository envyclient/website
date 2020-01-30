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
                        {{ Form::hidden('price', 10) }}
                        {{ Form::submit('10', ['class' => 'btn btn-secondary', 'style' => 'margin-right: 5px']) }}
                        {!! Form::close() !!}

                        {!! Form::open(['action' => 'PayPalController@create', 'method' => 'POST']) !!}
                        {{ Form::hidden('price', 15) }}
                        {{ Form::submit('15', ['class' => 'btn btn-secondary']) }}
                        {!! Form::close() !!}

                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Transactions</div>

                    <div class="card-body">

                        <table class="table">
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
                                    <td>{{ $transaction->amount }}</td>
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
