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
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.min.css" rel="stylesheet">
</head>

<style type="text/css">
    * {
        margin: 0;
        padding: 0;
    }

    .sidebar-menu {
        display: inline-block;
        float: left;
        width: 250px;
        height: 100vh;
        background-color: #303030;
    }

    .sidebar-menu .list-group-item-custom {
        width: 100%;
        color: #fff;
        text-shadow: 1px 1px #666;
        background-color: #1c1c1c;
        text-align: inherit;
        border: none;
    }

    .sidebar-menu .list-group-item-custom:hover,
    .sidebar-menu .list-group-item-custom:focus {
        z-index: 1;
        color: #fff;
        text-decoration: none;
        background-color: #0f8c1d;
        border: none;
    }

    .sidebar-menu .active {
        background-color: #0f8c1d !important;
    }

    .inner-navbar {
        width: calc(100vw - 15px);
    }

    .inner-navbar .navbar-brand {
        color: #fff;
        text-decoration: none;
        display: inline-block;
    }

    .container-inner-nav {
        width: 95%;
    }

    a.custom-badge {
        color: #fff;
        background-color: #303030;
        padding: 6px;
    }

    a.custom-badge:hover,
    a.custom-badge:focus {
        color: #fff;
        background-color: #303030;
    }

    a.custom-badge:focus,
    a.custom-badge.focus {
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
    }

    .custom-panel .card {
        transition: .05s all;
        margin-top: 10px;
        -webkit-box-shadow: 0 0 0 -200px rgba(209, 209, 209, 1);
        -moz-box-shadow: 0 0 0 -200px rgba(209, 209, 209, 1);
        box-shadow: 0 0 0 -200px rgba(209, 209, 209, 1);
    }

    .custom-panel .card:hover {
        transition: .05s all;
        -webkit-box-shadow: 14px 14px 17px -1px rgba(209, 209, 209, 1);
        -moz-box-shadow: 14px 14px 17px -1px rgba(209, 209, 209, 1);
        box-shadow: 14px 14px 17px -1px rgba(209, 209, 209, 1);
    }

    .custom-panel .card-body {
        background-color: #fff;
        border: none;
        font-weight: bold;
        color: #303030;
        text-align: left;
        font-size: 23px;
        padding-bottom: 0;
    }

    .custom-panel span.card-sub-title {
        color: #8f8f8f;
        text-align: left;
        font-size: 16px;
        font-weight: bold;
        padding: 15px 15px 0;
    }

    .custom-panel .card-footer {
        border: none;
        background-color: #fff;
        text-align: left;
        font-size: 13px;
        color: #a8a8a8;
        font-weight: bold;
    }

    .dashboard-content {
        height: calc(100vh - 59px);
        overflow-y: scroll;
    }

    a.color-red {
        color: red;
    }

    a.color-red:hover {
        color: #fff;
    }

    a.color-blue {
        color: #3490dc;
    }

    a.color-blue:hover {
        color: white;
    }
</style>

<body>
<div id="app">
    @include('inc.navbar.dashboard')
</div>
@yield('js')
</body>
</html>
