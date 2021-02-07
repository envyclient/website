@extends('layouts.dash')

@section('title', 'Users')

@section('content')
    <div style="width:98%;margin:0 auto">

        <div class="alert alert-dark" style="font-size:25px;">
            Stats
        </div>

        @livewire('users-stats')

        <!-- users chart -->
        <x-bar-chart name="users_chart"/>

        <!-- users table -->
        <div id="users-table" class="mt-3">
            <div class="alert alert-dark" style="font-size:25px;">
                User Management
                <span class="badge bg-secondary ms-2">
                    {{ \App\Models\User::where('current_account', '<>', null)->count() }}
                </span>
            </div>

            @livewire('users-table')
        </div>
    </div>
@endsection
