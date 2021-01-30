@extends('layouts.base')

@section('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
            height: 50px;
            padding-top: 12px;
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
                             width="128"
                             height="128"
                             class="mt-3 mb-3"
                             alt="logo">
                    </div>
                </a>
                <div class="list-group list-group-flush">
                    <h3 class="m-3 mt-3 text-white" style="font-size:16px;">
                        SETTINGS
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ Route::is('dashboard') ? 'active' : null }}"
                       href="{{ route('dashboard') }}">
                        <svg class="ms-1 me-3" style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/>
                        </svg>
                        Home
                    </a>
                    <a class="list-group-item list-group-item-custom {{ Route::is('pages.security') ? 'active' : null }}"
                       href="{{ route('pages.security') }}">
                        <svg class="ms-1 me-3" style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.1 14.8,9.5V11C15.4,11 16,11.6 16,12.3V15.8C16,16.4 15.4,17 14.7,17H9.2C8.6,17 8,16.4 8,15.7V12.2C8,11.6 8.6,11 9.2,11V9.5C9.2,8.1 10.6,7 12,7M12,8.2C11.2,8.2 10.5,8.7 10.5,9.5V11H13.5V9.5C13.5,8.7 12.8,8.2 12,8.2Z"/>
                        </svg>
                        Security
                    </a>

                    <h3 class="m-3 mt-5 text-white" style="font-size:16px;">
                        COMMUNITY
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ Route::is('pages.discord') ? 'active' : null }}"
                       href="{{ route('pages.discord') }}">
                        <svg class="ms-1 me-3" style="width:22px;height:22px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M22,24L16.75,19L17.38,21H4.5A2.5,2.5 0 0,1 2,18.5V3.5A2.5,2.5 0 0,1 4.5,1H19.5A2.5,2.5 0 0,1 22,3.5V24M12,6.8C9.32,6.8 7.44,7.95 7.44,7.95C8.47,7.03 10.27,6.5 10.27,6.5L10.1,6.33C8.41,6.36 6.88,7.53 6.88,7.53C5.16,11.12 5.27,14.22 5.27,14.22C6.67,16.03 8.75,15.9 8.75,15.9L9.46,15C8.21,14.73 7.42,13.62 7.42,13.62C7.42,13.62 9.3,14.9 12,14.9C14.7,14.9 16.58,13.62 16.58,13.62C16.58,13.62 15.79,14.73 14.54,15L15.25,15.9C15.25,15.9 17.33,16.03 18.73,14.22C18.73,14.22 18.84,11.12 17.12,7.53C17.12,7.53 15.59,6.36 13.9,6.33L13.73,6.5C13.73,6.5 15.53,7.03 16.56,7.95C16.56,7.95 14.68,6.8 12,6.8M9.93,10.59C10.58,10.59 11.11,11.16 11.1,11.86C11.1,12.55 10.58,13.13 9.93,13.13C9.29,13.13 8.77,12.55 8.77,11.86C8.77,11.16 9.28,10.59 9.93,10.59M14.1,10.59C14.75,10.59 15.27,11.16 15.27,11.86C15.27,12.55 14.75,13.13 14.1,13.13C13.46,13.13 12.94,12.55 12.94,11.86C12.94,11.16 13.45,10.59 14.1,10.59Z"/>
                        </svg>
                        Discord
                    </a>

                    <h3 class="m-3 mt-5 text-white" style="font-size:16px;">
                        BILLING
                    </h3>
                    <a class="list-group-item list-group-item-custom {{ Route::is('pages.subscriptions') ? 'active': null }}"
                       href="{{ route('pages.subscriptions') }}">
                        <svg class="ms-1 me-3" style="width:22px;height:22px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M19,8L15,12H18A6,6 0 0,1 12,18C11,18 10.03,17.75 9.2,17.3L7.74,18.76C8.97,19.54 10.43,20 12,20A8,8 0 0,0 20,12H23M6,12A6,6 0 0,1 12,6C13,6 13.97,6.25 14.8,6.7L16.26,5.24C15.03,4.46 13.57,4 12,4A8,8 0 0,0 4,12H1L5,16L9,12"/>
                        </svg>
                        Subscription
                    </a>
                </div>
                @admin
                <h3 class="m-3 mt-5 text-white" style="font-size:16px;">
                    ADMINISTRATOR
                </h3>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.users') ? 'active': null }}"
                   href="{{ route('admin.users') }}">
                    <svg class="ms-1 me-3" style="width:24px;height:24px;" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M16 17V19H2V17S2 13 9 13 16 17 16 17M12.5 7.5A3.5 3.5 0 1 0 9 11A3.5 3.5 0 0 0 12.5 7.5M15.94 13A5.32 5.32 0 0 1 18 17V19H22V17S22 13.37 15.94 13M15 4A3.39 3.39 0 0 0 13.07 4.59A5 5 0 0 1 13.07 10.41A3.39 3.39 0 0 0 15 11A3.5 3.5 0 0 0 15 4Z"/>
                    </svg>
                    Users
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.versions') ? 'active': null }}"
                   href="{{ route('admin.versions') }}">
                    <svg class="ms-1 me-3" style="width:24px;height:24px;" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M17,13L12,18L7,13H10V9H14V13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"/>
                    </svg>
                    Versions
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.referrals') ? 'active': null }}"
                   href="{{ route('admin.referrals') }}">
                    <svg class="ms-1 me-3" style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M3,11H5V13H3V11M11,5H13V9H11V5M9,11H13V15H11V13H9V11M15,11H17V13H19V11H21V13H19V15H21V19H19V21H17V19H13V21H11V17H15V15H17V13H15V11M19,19V15H17V19H19M15,3H21V9H15V3M17,5V7H19V5H17M3,3H9V9H3V3M5,5V7H7V5H5M3,15H9V21H3V15M5,17V19H7V17H5Z"/>
                    </svg>
                    Referrals Codes
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.notifications') ? 'active': null }}"
                   href="{{ route('admin.notifications') }}">
                    <svg class="ms-1 me-3" style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21"/>
                    </svg>
                    Notifications
                </a>
                <a class="list-group-item list-group-item-custom {{ Route::is('admin.sales') ? 'active': null }}"
                   href="{{ route('admin.sales') }}">
                    <svg class="ms-1 me-3" style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M3,6H21V18H3V6M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9M7,8A2,2 0 0,1 5,10V14A2,2 0 0,1 7,16H17A2,2 0 0,1 19,14V10A2,2 0 0,1 17,8H7Z"/>
                    </svg>
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
