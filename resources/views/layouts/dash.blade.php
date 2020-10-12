<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- fav icon -->
    <link rel="shortcut icon" href="{{ asset('/assets/logo_512x512.png') }}"/>

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
            crossorigin="anonymous" defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
            integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
            crossorigin="anonymous" defer></script>

    <!-- Charts.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <!-- Umami Analytics -->
    <script async defer data-website-id="0d3cae30-14ae-49d2-aac9-b176a4049cc0"
            src="https://umami.affanhaq.me/umami.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.min.css" rel="stylesheet">

    @livewireStyles

    <style type="text/css">
        #sidebar-wrapper {
            background-color: #303030;
            min-height: 100vh;
            margin-left: -15rem;
            transition: margin .25s ease-out;
        }

        #sidebar-wrapper .active {
            background-color: #0f8c1d !important;
        }

        .list-group-item-custom {
            color: #fff;
            text-shadow: 1px 1px #666;
            background-color: #1c1c1c;
            text-align: inherit;
            border: none;
        }

        .list-group-item-custom:hover,
        .list-group-item-custom:focus {
            color: #fff;
            text-decoration: none;
            background-color: #0f8c1d;
            border: none;
        }

        #sidebar-wrapper .list-group {
            width: 15rem;
        }

        #page-content-wrapper {
            min-width: 100vw;
        }

        .wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        .table-sticky thead th {
            position: sticky;
            top: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            .wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }
        }
    </style>
</head>
<body>
<div class="d-flex wrapper">

    <div class="d-inline-block" id="sidebar-wrapper">
        <div class="list-group">
            <a class="navbar-brand text-white">
                <div class="container text-center">
                    <img src="{{ asset('/assets/logo_512x512.png') }}"
                         style="width:128px;height:128px;margin-top:10px;margin-bottom:10px;">
                </div>
            </a>
            <div class="list-group list-group-flush">
                <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                    <small class="text-muted">SETTINGS</small>
                </h3>
                <a class="list-group-item list-group-item-custom {{ request()->routeIs('home') ? 'active': null }}"
                   href="{{ route('home') }}">
                    <i class="fas fa-user-circle p-2" style="margin-right:10px;"></i>
                    Home
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
                    <small class="text-muted">BILLING</small>
                </h3>
                <a class="list-group-item list-group-item-custom {{ request()->routeIs('pages.subscriptions') ? 'active': null }}"
                   href="{{ route('pages.subscriptions') }}">
                    <i class="fas fa-redo p-2" style="margin-right:10px;"></i>
                    Subscription
                </a>
            </div>
            @if(auth()->user()->admin)
                <h3 class="m-3 font-weight-bold" style="font-size:18px;padding-top:30px;">
                    <small class="text-muted">ADMINISTRATOR</small>
                </h3>
                <a class="list-group-item list-group-item-custom {{ request()->routeIs('admin.users') ? 'active': null }}"
                   href="{{ route('admin.users') }}">
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
            @endif
        </div>
    </div>

    <div id="page-content-wrapper">

        <!-- top navbar -->
        <nav class="navbar navbar-expand-lg shadow-sm" style="background:#242424 !important;">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                Envy Client | <span style="color:#888;">dashboard</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

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
        </nav>

        <br>

        <!-- notifications -->
        <div class="container">
            @include('inc.notifications')
        </div>

        @yield('content')
    </div>
</div>

@livewireScripts

<!-- Custom JS Scripts -->
@yield('js')

</body>
</html>
