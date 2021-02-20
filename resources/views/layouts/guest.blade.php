@extends('layouts.base')

@section('styles')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection

@section('body')
    @if(Route::is('terms'))
        @yield('content')
    @else
        <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    @endif
@endsection
