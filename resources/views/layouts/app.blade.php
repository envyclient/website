<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- fav icon -->
    <link rel="shortcut icon" href="{{ asset('/assets/logo_512x512.png') }}"/>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script async defer data-website-id="0d3cae30-14ae-49d2-aac9-b176a4049cc0" 
        src="https://umami.affanhaq.me/umami.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.min.css" rel="stylesheet">
</head>
<body>
<div>
    @include('inc.navbar.default')
    <div id="main">
        {{--        <div class="container">
                    @include('inc.notifications')
                </div>--}}
        @yield('content')
    </div>
</div>
@yield('js')
</body>
</html>
