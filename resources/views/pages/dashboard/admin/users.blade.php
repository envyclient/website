@extends('layouts.dash')

@section('title', 'Users')

@section('content')
    <div style="width:98%;margin:0 auto">

        <div class="alert alert-dark" style="font-size:25px;">
            Stats
        </div>

        <div class="text-center w-100 mb-3" style="display:block;">
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center bg-light">
                    <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                        <path fill="currentColor"
                              d="M16 17V19H2V17S2 13 9 13 16 17 16 17M12.5 7.5A3.5 3.5 0 1 0 9 11A3.5 3.5 0 0 0 12.5 7.5M15.94 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13M15 4A3.39 3.39 0 0 0 13.07 4.59A5 5 0 0 1 13.07 10.41A3.39 3.39 0 0 0 15 11A3.5 3.5 0 0 0 15 4Z"/>
                    </svg>
                    <h2>{{ $users['count'] }}</h2>
                    <h4>Total</h4>
                </div>
            </div>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center bg-light">
                    <svg style="width:48px;height:48px" viewBox="0 0 24 24" class="m-auto">
                        <path fill="currentColor"
                              d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.9L16.2,16.2Z"/>
                    </svg>
                    <h2>{{ $users['active'] }}</h2>
                    <h4>Currently Active</h4>
                </div>
            </div>

            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center bg-light">
                    <h2>{{ $today['users'] }}</h2>
                    <h4>Users Today</h4>
                </div>
            </div>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center bg-light">
                    <h2>{{ $today['subscriptions'] }}</h2>
                    <h4>Subs Today</h4>
                </div>
            </div>

            <br>

            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center bg-light">
                    <h2>{{ $subscriptions['free'] }}</h2>
                    <h4>Free Plan</h4>
                </div>
            </div>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center bg-light">
                    <h2>{{ $subscriptions['standard'] }}</h2>
                    <h4>Standard Plan</h4>
                </div>
            </div>
            <div class="col-md-2 m-1" style="display:inline-block;">
                <div class="shadow p-3 rounded text-center bg-light">
                    <h2>{{ $subscriptions['premium'] }}</h2>
                    <h4>Premium Plan</h4>
                </div>
            </div>
        </div>

        <!-- users chart -->
        <x-bar-chart name="users_chart"/>

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
