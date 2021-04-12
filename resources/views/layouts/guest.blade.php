@extends('layouts.base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('body')
    @if(Route::is('login', 'register', 'password.request', 'password.reset', 'setup-account', 'verification.notice', 'password.confirm', 'verification.verify'))
        <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    @else
        @yield('content')
    @endif
@endsection
