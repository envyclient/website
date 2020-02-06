@extends('layouts.app')

@section('content')

    <!-- Home Section -->
    <div class="jumbotron jumbotron-fluid text-center" style="background:#fff;margin-bottom:0px;">
        <div class="container-fluid p-lg-5">
            <h1 class="display-4">{{ config('app.name') }}</h1>
            <br>
            <p class="lead">
                Welcome to the {{ config('app.name') }} website. This is a Minecraft 1.8.8 client developed by Mat & Haq.
            <p>
            <div class="text-center">
                @guest
                    <a class="btn btn-primary btn-lg" href="/login">
                        <i class="fab fa-paypal"></i>
                        Purchase
                    </a>
                @else
                    <a class="btn btn-primary btn-lg" href="/home" role="button">
                        <i class="fas fa-bars"></i>
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
    <!-- Home Section -->

    <!-- Features Section -->
    <div class="jumbotron jumbotron-fluid text-center" style="background: #f9f9f9;border:none;margin-bottom:0;">
        <div class="container">
            <h1 style="padding-bottom:30px;">Features</h1>
            <hr class="w-50">
            <br>
            <div class="row">
                <div class="col text-center">
                    <div class="card bg-white mb-3 p-3"
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
                </div>
                <div class="col text-center">
                    <div class="card bg-white mb-3 p-3"
                         style="min-width: 18rem;max-width: 18rem;margin:0 auto;border-top:3px solid #4aa0e6;">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold p-1">Cloud</h5>
                            <p class="card-text p-1">
                                All of your settings & configs will be safely stored online.
                            </p>
                            <a class="btn btn-info text-white">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="card bg-white mb-3 p-3"
                         style="min-width: 18rem;max-width: 18rem;margin:0 auto;border-top:3px solid #227dc7;">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold p-1">Design</h5>
                            <p class="card-text p-1">
                                Design of the client is simplistic, therefor it's really easy to use.
                            </p>
                            <a class="btn btn-primary text-white">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Features Section -->

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
    <!-- ShowCase Section End -->

    <!-- Pricing Section -->
    <div class="jumbotron jumbotron-fluid text-center" style="background:#e5e5e5; border:none;margin-bottom:0;">
        <h1 style="padding-bottom:30px;">Pricing</h1>
        <hr class="w-50">
        <br>
        <div class="container">
            <div class="row" style="margin:0 auto;">
                <!-- Client  -->
                <div class="col-md-5 col-sm-5" style="margin:0 auto;">
                    <div class="pricingTable green">
                        <div class="pricingTable-header">
                                <span class="heading">
                                    <h3>Envy Client</h3>
                                </span>
                            <span class="price-value">$15 <span>monthly</span></span>
                        </div>
                        <div class="pricingContent">
                            <ul class="text-center">
                                <li>Cloud Storage <i class="fa fa-check" style="color:green;"></i></li>
                                <li>Clean Design <i class="fa fa-check" style="color:green;"></i></li>
                                <li>The Altening support <i class="fa fa-check" style="color:green;"></i></li>
                                <li>Easy to use <i class="fa fa-check" style="color:green;"></i></li>
                            </ul>
                        </div>
                        <div class="pricingTable-sign-up">
                            @guest
                                <a href="/login" class="btn btn-block">Login</a>
                            @else
                                <a href="/login" class="btn btn-block">Dasboard</a>
                            @endguest
                        </div>
                    </div>
                </div>
                <!-- Client End -->
            </div>
        </div>
    </div>
    <!-- Pricing Section End -->

@endsection
