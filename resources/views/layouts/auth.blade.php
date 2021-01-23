<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="google" content="nositelinkssearchbox"/>
    <meta name="google" content="notranslate"/>
    <meta name="google" content="nopagereadaloud"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- fav icon -->
    <link rel="icon" sizes="16x16" type="image/png" href="{{ asset('favicon-16.png') }}">
    <link rel="icon" sizes="32x32" type="image/png" href="{{ asset('favicon-32.png') }}">

    <!-- Cloudflare Web Analytics -->
    <script defer src='https://static.cloudflareinsights.com/beacon.min.js'
            data-cf-beacon='{"token": "84c979ca91574da6bd382f7062ce5002"}'></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    @livewireStyles
</head>
<body>
<div>
    @if(Route::is('pages.terms'))
        @yield('content')
    @else
        <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    @endif
</div>

@livewireScripts

</body>
</html>
