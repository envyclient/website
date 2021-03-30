@extends('layouts.dash')

@section('title', 'Users')

@section('content')
    <div id="users-table" class="mt-3">
        <div class="alert alert-dark fs-4">
            Users
            <span class="badge bg-secondary ms-2">
                    {{ \App\Models\User::count() }}
                </span>
            <span class="badge bg-secondary ms-2">
                    {{ \App\Models\User::whereNotNull('current_account')->count() }}
                </span>
        </div>

        @livewire('users-table')
    </div>
@endsection
