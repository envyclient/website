@extends('layouts.dash')

@section('title', 'Users')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div id="users-table" class="mt-3">
            <div class="alert alert-dark" style="font-size:25px;">
                User Management
                <span class="badge bg-secondary ms-2">
                    {{ \App\Models\User::count() }}
                </span>
                <span class="badge bg-secondary ms-2">
                    {{ \App\Models\User::where('current_account', '<>', null)->count() }}
                </span>
            </div>

            @livewire('users-table')
        </div>
    </div>
@endsection
