@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">

        <!-- users chart -->
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Stats
            </div>
            <div style="height: 300px">
                {!! $chart->container() !!}
            </div>
        </div>

        <br>

        <!-- users table -->
        <div id="users-table">
            <div class="alert alert-dark" style="font-size:25px;">
                User Management
                <span class="badge bg-secondary mx-2">
                    {{ \App\Models\User::count() }}
                </span>
                <span class="badge bg-secondary mx-2">
                    {{ \App\Models\User::where('current_account', '<>', null)->count() }}
                </span>
            </div>

            @livewire('users-table')
        </div>
    </div>
@endsection

@section('js')
    {!! $chart->script() !!}
@endsection
