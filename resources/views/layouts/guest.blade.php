@extends('layouts.base')

@section('body')
    @if(Route::is('stripe-source.show', 'login', 'register', 'password.request', 'password.reset', 'verification.notice', 'password.confirm', 'verification.verify'))
        <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    @else
        @yield('content')
    @endif
@endsection
