<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.min.css" rel="stylesheet">

    <style type="text/css">
        .pricingTable {
            text-align: center;
            transition: all 0.5s ease 0s;
        }

        .pricingTable:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .pricingTable .pricingTable-header {
            color: #feffff;
        }

        .pricingTable .heading {
            display: block;
            padding-top: 25px;
        }

        .pricingTable .heading > h3 {
            font-size: 20px;
            margin: 0;
            text-transform: capitalize;
        }

        .pricingTable .subtitle {
            display: block;
            font-size: 13px;
            margin-top: 5px;
            text-transform: capitalize;
        }

        .pricingTable .price-value {
            display: block;
            font-size: 64px;
            font-weight: 700;
            padding-bottom: 25px;
        }

        .pricingTable .price-value span {
            display: block;
            font-size: 14px;
            line-height: 20px;
            text-transform: uppercase;
        }

        .pricingTable .pricingContent {
            text-transform: capitalize;
            background: #fbfbfb;
            color: #fefeff;
        }

        .pricingTable .pricingContent ul {
            list-style: none;
            padding: 15px 20px 10px;
            margin: 0;
            text-align: left;
        }

        .pricingTable .pricingContent ul li {
            font-size: 14px;
            padding: 12px 0;
            border-bottom: 1px dashed #e1e1e1;
            color: #9da1ad;
        }

        .pricingTable .pricingContent ul li i {
            font-size: 14px;
            float: right;
        }

        .pricingTable .pricingTable-sign-up {
            padding: 20px 0;
            background: #fbfbfb;
            color: #fff;
            text-transform: capitalize;
        }

        .pricingTable .btn-block {
            width: 60%;
            margin: 0 auto;
            font-size: 17px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            border-radius: 5px;
            padding: 10px;
            transition: all 0.5s ease 0s;
        }

        .pricingTable .btn-block:before {
            content: "\f007";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-right: 10px;
        }

        .pricingTable.blue .pricingTable-header,
        .pricingTable.blue .btn-block {
            background: #727cb6;
        }

        .pricingTable.pink .pricingTable-header,
        .pricingTable.pink .btn-block {
            background: #ed687c;
        }

        .pricingTable.orange .pricingTable-header,
        .pricingTable.orange .btn-block {
            background: #e67e22;
        }

        .pricingTable.green .pricingTable-header,
        .pricingTable.green .btn-block {
            background: #008b8b;
        }

        .pricingTable.blue .btn-block:hover,
        .pricingTable.pink .btn-block:hover,
        .pricingTable.orange .btn-block:hover,
        .pricingTable.green .btn-block:hover {
            background: #e6e6e6;
            color: #939393;
        }

        @media screen and (max-width: 990px) {
            .pricingTable {
                margin-bottom: 20px;
            }
        }
    </style>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">


                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else

                        {{-- TODO: link to purchase plans page --}}
                        <div class="m-2">
                            <a href="#" class="badge badge-secondary fa-1x">
                                <i class="fas fa-coins" style="padding-right: 5px;"></i>
                                {{ Auth::user()->balance == null ? 0 :  Auth::user()->balance }}
                            </a>
                        </div>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            @include('inc.notifications')
        </div>
        @yield('content')
    </main>
</div>
</body>
</html>
