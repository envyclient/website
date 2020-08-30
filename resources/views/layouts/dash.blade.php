<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- fav icon -->
    <link rel="shortcut icon" href="{{ asset('/assets/logo_512x512.png') }}"/>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script async defer data-website-id="0d3cae30-14ae-49d2-aac9-b176a4049cc0"
            src="https://umami.affanhaq.me/umami.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.min.css" rel="stylesheet">

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

        .table-sticky thead th {
            position: sticky;
            top: 0;
        }
    </style>
</head>
<body>
<div id="app">

    <!-- vuejs notifications -->
    <notification></notification>

    <!-- side navbar -->
    <div class="sidebar-menu d-inline-block">
        <div class="list-group">
            <a class="navbar-brand text-white" href="{{ route('home') }}">
                <div class="container text-center">
                    <img src="{{ asset('/assets/logo_512x512.png') }}"
                         style="width:128px;height:128px;margin-top:10px;margin-bottom:10px;">
                </div>
            </a>
            @if(request()->routeIs('admin') || request()->routeIs('admin.*'))
                <div class="list-group list-group-flush">
                    <br>
                    <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                        <small class="text-muted">ADMINISTRATOR</small>
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ request()->routeIs('admin') ? 'active': null }}"
                       href="{{ route('admin') }}">
                        <i class="fas fa-users p-2" style="margin-right:10px;"></i>
                        Users
                    </a>
                    <a class="list-group-item list-group-item-custom {{ request()->routeIs('admin.versions') ? 'active': null }}"
                       href="{{ route('admin.versions') }}">
                        <i class="fas fa-download p-2" style="margin-right:10px;"></i>
                        Versions
                    </a>
                    <a class="list-group-item list-group-item-custom {{ request()->routeIs('admin.sessions') ? 'active': null }}"
                       href="{{ route('admin.sessions') }}">
                        <i class="fas fa-chart-area p-2" style="margin-right:10px;"></i>
                        Game Sessions
                    </a>
                </div>
            @else
                <div class="list-group list-group-flush">
                    <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                        <small class="text-white">SETTINGS</small>
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ request()->routeIs('home') ? 'active': null }}"
                       href="{{ route('home') }}">
                        <i class="fas fa-user-circle p-2" style="margin-right:10px;"></i>
                        Profile
                    </a>
                    <a class="list-group-item list-group-item-custom {{ request()->routeIs('pages.security') ? 'active': null }}"
                       href="{{ route('pages.security') }}">
                        <i class="fas fa-lock p-2" style="margin-right:10px;"></i>
                        Security
                    </a>
                    <a class="list-group-item list-group-item-custom" href="https://forums.envyclient.com/">
                        <i class="fas fa-comments p-2" style="margin-right:10px;"></i>
                        Forums
                    </a>
                    <h3 class="m-3 font-weight-bold" style="font-size:18px;padding-top:30px;">
                        <small class="text-white">BILLING</small>
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ request()->routeIs('pages.subscriptions') ? 'active': null }}"
                       href="{{ route('pages.subscriptions') }}">
                        <i class="fas fa-redo p-2" style="margin-right:10px;"></i>
                        Subscription
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- top navbar -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="background:#242424 !important;">
        <div class="container-inner-nav text-white m-auto">
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <a class="navbar-brand text-white" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }} | <span
                            style="color:#888;">{{ request()->is("/") ? 'dashboard' : 'admin' }}</span>
                    </a>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto text-white">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ auth()->user()->image() }}?s=32" class="rounded-circle mr-1"
                                 alt="user image">
                            {{ auth()->user()->name }} <span class="caret"></span>
                        </a>
                        @include('inc.navbar.dropdown')
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="main" class="dashboard-content mt-3">

        <!-- notifications -->
        <div class="container">
            @include('inc.notifications')
        </div>

        <!-- content -->
        @yield('content')
    </div>
</div>
@yield('js')
</body>
</html>
