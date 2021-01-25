@extends('layouts.base')

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.min.css">
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
@endsection

@section('body')
    <div class="d-flex wrapper">

        <div class="d-inline-block" id="sidebar-wrapper">
            <div class="list-group">
                <a class="navbar-brand text-white">
                    <div class="container text-center">
                        <img src="{{ asset('logo.svg') }}"
                             style="width:128px;height:128px;margin-top:10px;margin-bottom:10px;"
                             alt="logo">
                    </div>
                </a>
                <div class="list-group list-group-flush">
                    <h3 class="m-3 text-white" style="font-size:16px;">
                        SETTINGS
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ Route::is('dashboard') ? 'active' : null }}"
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-user-circle p-2" style="margin-right:10px;"></i>
                        Home
                    </a>
                    <a class="list-group-item list-group-item-custom {{ Route::is('pages.security') ? 'active' : null }}"
                       href="{{ route('pages.security') }}">
                        <i class="fas fa-lock p-2" style="margin-right:10px;"></i>
                        Security
                    </a>

                    <h3 class="m-3 text-white" style="font-size:16px;padding-top:30px;">
                        COMMUNITY
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ Route::is('pages.discord') ? 'active' : null }}"
                       href="{{ route('pages.discord') }}">
                        <i class="fab fa-discord p-2" style="margin-right:12px;"></i>
                        Discord
                    </a>

                    <h3 class="m-3 text-white" style="font-size:16px;padding-top:30px;">
                        BILLING
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ Route::is('pages.subscriptions') ? 'active': null }}"
                       href="{{ route('pages.subscriptions') }}">
                        <i class="fas fa-sync p-2" style="padding-right:10px;"></i>
                        Subscription
                    </a>
                </div>
                @admin
                <h3 class="m-3 text-white" style="font-size:16px;padding-top:30px;">
                    ADMINISTRATOR
                </h3>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.users') ? 'active': null }}"
                   href="{{ route('admin.users') }}">
                    <i class="fas fa-users p-2" style="margin-right:10px;"></i>
                    Users
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.versions') ? 'active': null }}"
                   href="{{ route('admin.versions') }}">
                    <i class="fas fa-download p-2" style="margin-right:10px;"></i>
                    Versions
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.referrals') ? 'active': null }}"
                   href="{{ route('admin.referrals') }}">
                    <i class="fas fa-qrcode p-2" style="margin-right:10px;"></i>
                    Referrals Codes
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.notifications') ? 'active': null }}"
                   href="{{ route('admin.notifications') }}">
                    <i class="fas fa-bell p-2" style="margin-right:10px;"></i>
                    Notifications
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.sales') ? 'active': null }}"
                   href="{{ route('admin.sales') }}">
                    <i class="fas fa-coins p-2" style="margin-right:10px;"></i>
                    Sales
                </a>
                @endadmin
            </div>
        </div>

        <div id="page-content-wrapper">

            <!-- top navbar -->
            <nav class="navbar navbar-expand-lg shadow-sm navbar-dark bg-dark"
                 style="background-color: #303030 !important;">
                <a class="navbar-brand text-white mx-3" href="/">
                    Envy Client | <span class="text-muted">dashboard</span>
                </a>

                <button class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown"
                               class="nav-link dropdown-toggle text-white"
                               href="#"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                <img src="{{ auth()->user()->image }}"
                                     class="rounded-circle mx-1"
                                     alt="user image"
                                     width="32px"
                                     height="32px">
                                {{ auth()->user()->name }} <span class="caret"></span>
                            </a>
                            @include('inc.navbar.dropdown')
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- notifications -->
            <div class="container mt-3">
                @include('inc.notifications')
            </div>

            <div class="mt-3">
                @yield('content')
            </div>
        </div>
    </div>

    @yield('js')
@endsection
