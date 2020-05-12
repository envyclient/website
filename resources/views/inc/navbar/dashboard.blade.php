@if(request()->is("dashboard"))
    <div class="sidebar-menu d-inline-block">
        <div class="list-group">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                <div class="container text-center">
                    <img src="{{ asset('/assets/logo_512x512.png') }}"
                         style="width:128px;height:128px;margin-top:10px;margin-bottom:10px;">
                </div>
            </a>
            <div class="list-group list-group-flush">
                <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                    <small class="text-white">SETTINGS</small>
                </h3>
                <a class="list-group-item list-group-item-custom active" data-toggle="list" href="#profile"
                   style="cursor:pointer;">
                    <i class="fas fa-user-circle p-2" style="margin-right:10px;"></i>
                    Profile
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#security"
                   style="cursor:pointer;">
                    <i class="fas fa-lock p-2" style="margin-right:10px;"></i>
                    Security
                </a>
                <a class="list-group-item list-group-item-custom" href="https://discord.com/invite/kfdEehR"
                   style="cursor:pointer;">
                    <i class="fab fa-discord p-2" style="margin-right:10px;"></i>
                    Discord
                </a>
                <h3 class="m-3 font-weight-bold" style="font-size:18px;padding-top:30px;">
                    <small class="text-white">BILLING</small>
                </h3>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#subscription"
                   style="cursor:pointer;">
                    <i class="fas fa-redo p-2" style="margin-right:10px;"></i>
                    Subscription
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#credits"
                   style="cursor:pointer;">
                    <i class="fas fa-credit-card p-2" style="margin-right:10px;"></i>
                    Add Credits
                </a>
                @if(count($transactions) > 0)
                    <a class="list-group-item list-group-item-custom" data-toggle="list" href="#invoices"
                       style="cursor:pointer;">
                        <i class="fas fa-shopping-cart p-2" style="margin-right:10px;"></i>
                        Transactions
                    </a>
                @endif
            </div>
        </div>
    </div>
@elseif(request()->is("admin"))
    <div class="sidebar-menu d-inline-block">
        <div class="list-group">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                <div class="container text-center">
                    <img src="{{ asset('/assets/logo_512x512.png') }}"
                         style="width:128px;height:128px;margin-top:10px;margin-bottom:10px;">
                </div>
            </a>
            <div class="list-group list-group-flush">
                <br>
                <h3 class="m-3 font-weight-bold" style="font-size:18px;">
                    <small class="text-muted ">ADMINISTRATOR</small>
                </h3>
                <a class="list-group-item list-group-item-custom active" data-toggle="list" href="#transactions"
                   style="cursor:pointer;">
                    <i class="fas fa-balance-scale p-2" style="margin-right:10px;"></i>
                    Transactions
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#users"
                   style="cursor:pointer;">
                    <i class="fas fa-users p-2" style="margin-right:10px;"></i>
                    Users
                </a>
                <a class="list-group-item list-group-item-custom" data-toggle="list" href="#downloads"
                   style="cursor:pointer;">
                    <i class="fas fa-download p-2" style="margin-right:10px;"></i>
                    Downloads
                </a>
            </div>
        </div>
    </div>
@endif
<nav class="container-fluid" style="height:100vh;padding:0;">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="background:#242424 !important;">
        <div class="container-inner-nav text-white m-auto">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <a class="navbar-brand text-white" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }} | <span
                            style="color:#888;">{{explode("/", request()->getRequestUri())[1]}}</span>
                    </a>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto text-white">
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
                        <div class="m-2">
                            <a class="badge custom-badge fa-1x" style="color: #fff;">
                                <i class="fas fa-coins" style="padding-right: 5px;"></i>
                                {{ Auth::user()->balance == null ? 0 :  Auth::user()->balance }}
                            </a>
                        </div>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            @include('inc.navbar.components.dropdown')
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="dashboard-content">
        <br>
        @include('inc.notifications')
        @yield('content')
        <br>
    </div>
</nav>
