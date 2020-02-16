@extends('layouts.app')

@section('content')

    <!-- Home Section -->
    <div class="jumbotron jumbotron-fluid" style="background:#fff;">
        <div class="row" style="margin:0 auto;width:fit-content;">
            <div class="flex-column">
                <img class="img-fluid" style="min-width:256px;min-height:256px;"
                     src="{{ asset('/assets/logo_512x512.png') }}" width="512px" height="512px">
            </div>
            <div class="col d-flex flex-column">
                <div class="p-2">
                    <h1 class="display-4">{{ config('app.name') }}</h1>
                    <br>
                    <p class="lead">
                        Welcome to the {{ config('app.name') }} website. This is a Minecraft 1.8.8 client developed by
                        Mat &
                        Haq.
                    <p>
                        @guest
                            <a class="btn btn-primary btn-lg" href="{{ route('login') }}">
                                <i class="fab fa-paypal"></i>
                                Purchase
                            </a>
                        @else
                            <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}" role="button">
                                <i class="fas fa-bars"></i>
                                Dashboard
                            </a>
                        @endguest
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="jumbotron jumbotron-fluid text-center" style="background: #f9f9f9;border:none;margin-bottom:0;">
        <div class="container">
            <h1 style="padding-bottom:30px;">Features</h1>
            <hr class="w-50">
            <br>
            <div class="card-deck mb-3 text-center">
                <div class="card bg-white mb-4 p-3"
                     style="min-width: 18rem;max-width: 18rem;margin:0 auto;border-top:3px solid #4aa0e6;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold p-1">Cloud</h5>
                        <p class="card-text p-1 mt-4">
                            All of your settings & configs will be safely stored online.
                        </p>
                    </div>
                </div>
                <div class="card bg-white mb-4 p-3"
                     style="min-width: 18rem;max-width: 18rem;margin:0 auto;border-top:3px solid #2fa360;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold p-1">AlphaAntiLeak</h5>
                        <p class="card-text p-1">
                            Our Service is using AlphaAntiLeak
                        </p>
                        <a class="btn btn-success text-white" href="https://alphaantileak.net/#privacy"
                           target="_blank">Learn More</a>
                    </div>
                </div>
                <div class="card bg-white mb-4 p-3"
                     style="min-width: 18rem;max-width: 18rem;margin:0 auto;border-top:3px solid #227dc7;">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold p-1">Design</h5>
                        <p class="card-text p-1 mt-4">
                            Design of the client is simplistic, therefor it's really easy to use.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ShowCase Section -->
    <div class="jumbotron jumbotron-fluid text-center" style="background:#efefef; border:none;margin-bottom:0;">
        <h1 style="padding-bottom:30px;">ShowCase</h1>
        <hr class="w-50">
        <br>
        <div class="row" style="margin:0 auto;">
            <div class="col-sm-5" style="margin:0 auto;">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Rh-Fn0zHVi8"></iframe>
                </div>
            </div>
            <div class="col-sm-5" style="margin:0 auto;">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.youtube.com/embed/zwJRWAB4fM4"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="jumbotron jumbotron-fluid text-center" style="background:#e5e5e5; border:none;margin-bottom:0;">
        <h1 style="padding-bottom:30px;">Pricing</h1>
        <hr class="w-50">
        <br>
        <div class="container">
            <div class="card-deck mb-3 text-center" style="max-width: 55em;margin:0 auto;">
                <div class="card mb-4 box-shadow">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Monthly</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$7 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>Ability to change capes</li>
                            <li>5 config slots</li>
                        </ul>
                        @guest
                            <a type="button" class="btn btn-lg btn-block btn-primary"
                               href="{{ route('login') }}">Login</a>
                        @else
                            <a type="button" class="btn btn-lg btn-block btn-primary"
                               href="{{ route('dashboard') }}">Purchase</a>
                        @endguest
                    </div>
                </div>
                <div class="card mb-4 box-shadow">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Lifetime</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$40 <small class="text-muted">/ &infin;</small>
                        </h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>Ability to change capes</li>
                            <li>15 config slots</li>
                        </ul>
                        @guest
                            <a type="button" class="btn btn-lg btn-block btn-primary"
                               href="{{ route('login') }}">Login</a>
                        @else
                            <a type="button" class="btn btn-lg btn-block btn-primary"
                               href="{{ route('dashboard') }}">Purchase</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small blue-grey lighten-5" style="background-color:#e0e0e0;">
        <div style="background-color: #ffff;">
            <div class="container">
                <!-- Grid row-->
                <div class="row py-4 d-flex align-items-center">

                    <!-- Grid column -->
                    <div class="col-md-6 col-lg-5 text-center text-md-left mb-4 mb-md-0">
                        <h6 class="mb-0">Get connected with us on social networks!</h6>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-6 col-lg-7 text-center text-md-right">
                        <!-- Facebook -->
                        <a class="fb-ic">
                            <i class="fab fa-facebook-f white-text mr-4"> </i>
                        </a>

                        <!-- Twitter -->
                        <a class="tw-ic">
                            <i class="fab fa-twitter white-text mr-4"> </i>
                        </a>

                        <!-- Google +-->
                        <a class="gplus-ic">
                            <i class="fab fa-google-plus-g white-text mr-4"> </i>
                        </a>

                        <!--Linkedin -->
                        <a class="li-ic">
                            <i class="fab fa-linkedin-in white-text mr-4"> </i>
                        </a>

                        <!--Instagram-->
                        <a class="ins-ic">
                            <i class="fab fa-instagram white-text"> </i>
                        </a>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row-->
            </div>
        </div>
    </footer>
@endsection
