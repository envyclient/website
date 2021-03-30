@extends('layouts.base')

@section('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .anim:hover {
            transition: 50ms;
            transform: scale(1.05);
        }
    </style>
@endsection

@section('body')
    <div class="container-fluid">

        <div class="row">
            <header class="navbar navbar-dark sticky-top bg-dark p-0 col-lg-10 ms-sm-auto">
                <a class="navbar-brand me-0 px-3" href="/">
                    Envy Client | <span class="text-muted">dashboard</span>
                </a>
                <button class="navbar-toggler position-absolute d-lg-none collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </header>

            <nav id="sidebarMenu" class="col-lg-2 d-lg-block sidebar collapse">

                <div class="d-flex flex-column h-100">

                    <div class="text-center">
                        <img src="{{ asset('logo.svg') }}"
                             width="128"
                             height="128"
                             alt="logo">
                    </div>

                    <div class="mt-4">
                        <h3 class="sidebar-heading m-3 text-white">
                            Dashboard
                        </h3>
                        <ul class="nav flex-column">
                            <li>
                                <a class="nav-link {{ Route::is('home') ? 'active' : null }}"
                                   href="{{ route('home') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path fill-rule="evenodd"
                                              d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                                        <path fill-rule="evenodd"
                                              d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                                    </svg>
                                    Home
                                </a>
                            </li>
                            <li>
                                <a class="nav-link {{ Route::is('home.profile') ? 'active' : null }}"
                                   href="{{ route('home.profile') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                        <path fill-rule="evenodd"
                                              d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                    </svg>
                                    Profile
                                </a>
                            </li>
                        </ul>

                        <h3 class="sidebar-heading m-3 mt-5 text-white">
                            Community
                        </h3>
                        <ul class="nav flex-column mb-2">
                            <li>
                                <a class="nav-link {{ Route::is('home.discord') ? 'active' : null }}"
                                   href="{{ route('home.discord') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path
                                            d="M6.552 6.712c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888.008-.488-.36-.888-.816-.888zm2.92 0c-.456 0-.816.4-.816.888s.368.888.816.888c.456 0 .816-.4.816-.888s-.36-.888-.816-.888z"/>
                                        <path
                                            d="M13.36 0H2.64C1.736 0 1 .736 1 1.648v10.816c0 .912.736 1.648 1.64 1.648h9.072l-.424-1.48 1.024.952.968.896L15 16V1.648C15 .736 14.264 0 13.36 0zm-3.088 10.448s-.288-.344-.528-.648c1.048-.296 1.448-.952 1.448-.952-.328.216-.64.368-.92.472-.4.168-.784.28-1.16.344a5.604 5.604 0 0 1-2.072-.008 6.716 6.716 0 0 1-1.176-.344 4.688 4.688 0 0 1-.584-.272c-.024-.016-.048-.024-.072-.04-.016-.008-.024-.016-.032-.024-.144-.08-.224-.136-.224-.136s.384.64 1.4.944c-.24.304-.536.664-.536.664-1.768-.056-2.44-1.216-2.44-1.216 0-2.576 1.152-4.664 1.152-4.664 1.152-.864 2.248-.84 2.248-.84l.08.096c-1.44.416-2.104 1.048-2.104 1.048s.176-.096.472-.232c.856-.376 1.536-.48 1.816-.504.048-.008.088-.016.136-.016a6.521 6.521 0 0 1 4.024.752s-.632-.6-1.992-1.016l.112-.128s1.096-.024 2.248.84c0 0 1.152 2.088 1.152 4.664 0 0-.68 1.16-2.448 1.216z"/>
                                    </svg>
                                    Discord
                                </a>
                            </li>
                        </ul>

                        <h3 class="sidebar-heading m-3 mt-5 text-white">
                            Billing
                        </h3>
                        <ul class="nav flex-column mb-2">
                            <li>
                                <a class="nav-link {{ Route::is('home.subscription') ? 'active' : null }}"
                                   href="{{ route('home.subscription') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7H0zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1z"/>
                                    </svg>
                                    Subscription
                                </a>
                            </li>
                        </ul>

                        @admin
                        <h3 class="sidebar-heading m-3 mt-5 text-white">
                            Administrator
                        </h3>
                        <ul class="nav flex-column mb-2">
                            <li>
                                <a class="nav-link {{ Route::is('admin.users') ? 'active' : null }}"
                                   href="{{ route('admin.users') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path
                                            d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                        <path fill-rule="evenodd"
                                              d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                                    </svg>
                                    Users
                                </a>
                            </li>
                            <li>
                                <a class="nav-link {{ Route::is('admin.versions') ? 'active' : null }}"
                                   href="{{ route('admin.versions') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path
                                            d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 6.854l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708z"/>
                                    </svg>
                                    Versions
                                </a>
                            </li>
                            <li>
                                <a class="nav-link {{ Route::is('admin.referrals') ? 'active' : null }}"
                                   href="{{ route('admin.referrals') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path
                                            d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z"/>
                                    </svg>
                                    Referral Codes
                                </a>
                            </li>
                            <li>
                                <a class="nav-link {{ Route::is('admin.license-requests') ? 'active' : null }}"
                                   href="{{ route('admin.license-requests') }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"
                                         style="margin: 0 10px 3px 0;">
                                        <path
                                            d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.122C.002 7.343.01 6.6.064 5.78l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                                    </svg>
                                    License Requests
                                </a>
                            </li>
                        </ul>
                        @endadmin
                    </div>

                    <footer class="footer mt-auto py-2" style="background-color: #1c1c1c; width: inherit;">
                        <div class="container">
                            @livewire('user.show-profile-image')
                        </div>
                    </footer>
                </div>
            </nav>

            <main class="col-lg-10 ms-sm-auto">

                <!-- notifications -->
                <div class="container mt-3">
                    @include('inc.notifications')
                </div>

                <div class="mt-3">
                    @yield('content')
                </div>
            </main>

        </div>

    </div>
    @yield('js')
@endsection
