<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="nositelinkssearchbox">
    <meta name="google" content="notranslate">
    <meta name="google" content="nopagereadaloud">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('title')
        <title>@yield('title') - Envy Client</title>
    @else
        <title>Envy Client</title>
    @endif

<!-- Favicon -->
    <link rel="icon" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="icon" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('android-chrome-192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('android-chrome-512.png') }}">

    <!-- Open Graph Protocol -->
    <meta property="og:title" content="Envy Client">
    <meta property="og:description" content="Official website of Envy Client">
    <meta property="og:image" content="{{ asset('android-chrome-512.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">

    <!-- Scripts -->
    @yield('scripts')
    <script defer src='https://static.cloudflareinsights.com/beacon.min.js'
            data-cf-beacon='{"token": "84c979ca91574da6bd382f7062ce5002"}'></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">

    <!-- Styles -->
    @yield('styles')
    @livewireStyles
</head>

<body>
@yield('body')

@livewireScripts
</body>
</html>