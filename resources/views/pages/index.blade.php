@extends('layouts.app')

@section('content')

    <div class="jumbotron jumbotron-fluid text-center" style="background:#f9f9f9;">
        <div class="container-fluid">
            <h1 class="display-4">{{ config('app.name') }}</h1>
            <p class="lead">
                Welcome to the {{ config('app.name') }} website. This is a Minecraft 1.8.8 client developed by Mat.
            <p>
            <h5 style="color:goldenrod;">Depression 2.0 is currently in works</h5>
            <br>
            <div class="text-center">
                @guest
                    <a class="btn btn-primary btn-lg" data-toggle="modal" data-target="#termsModal" role="button"
                       href="">
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

    <div class="jumbotron jumbotron-fluid text-center" style="background: #fff; border: 1px solid rgba(34,36,38,.15);">
        <div class="container">
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
                                All of your settings & configs will be securily saved online.
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

    <div style="width:100%;margin:0 auto;text-align:center;display:block;">
        <h1 style="padding-bottom:30px;">ShowCase</h1>
        <div class="row" style="margin:0 auto;">
            <div class="col-sm-6" style="margin:0 auto;">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Rh-Fn0zHVi8"></iframe>
                </div>
            </div>
            <div class="col-sm-6" style="margin:0 auto;">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.youtube.com/embed/zwJRWAB4fM4"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="jumbotron jumbotron-fluid text-center"
         style="background: #fff; border-top: 1px solid rgba(34,36,38,.15); margin-bottom:0; margin-top:30px; background:#ecf0f1;">
        <div class="container">
            <div class="card-deck">
                <div class="card" style="border-top:3px solid rgba(192, 57, 43,1.0);">
                    <div class="card-body">
                        <h5 class="card-title" style="color:rgba(192, 57, 43,1.0); font-weight: bold;">
                            Client Developer
                        </h5>
                        <p class="card-text" style="font-weight: bold;">
                        <h3 class="p-4">mat1337</h3>
                        <a class="footer-icon git p-3" target="_blank" href="https://github.com/Mat1337">
                            <i class="fab fa-github" style="color:#000;font-size:20px;"> </i>
                        </a>
                    </div>
                </div>
                <div class="card" style="border-top:3px solid rgba(46, 204, 113,1.0);">
                    <div class="card-body">
                        <h5 class="card-title" style="color:rgba(46, 204, 113,1.0); font-weight: bold;">
                            Web Developer
                        </h5>
                        <p class="card-text" style="font-weight: bold;">
                        <h3 class="p-4">haq</h3>
                        <a class="footer-icon git p-3" target="_blank" href="https://github.com/haq">
                            <i class="fab fa-github" style="color:#000;font-size:20px;"> </i>
                        </a>
                        <a class="footer-icon twitter p-3" target="_blank" href="https://twitter.com/haaaqs">
                            <i class="fab fa-twitter" style="color:#000;font-size:20px;"> </i>
                        </a>
                        <a class="footer-icon youtube p-3" target="_blank"
                           href="https://www.youtube.com/channel/UCwFRVvetOF3DqNj8z5-4d_A">
                            <i class="fab fa-youtube" style="color:#000;font-size:20px;"></i>
                        </a>
                        <a class="footer-icon mail p-3" target="_blank" href="mailto:me@affanhaq.me">
                            <i class="fas fa-envelope" style="color:#000;font-size:20px;"> </i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
         aria-hidden="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        <i class="fas fa-balance-scale"></i> Terms of Service
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="text-center" style="font-weight:bold;">
                        By purchasing Deprssion Client you agree to following terms.
                    </div>


                    <hr style="width:25%;">

                    1) Creaking / Leaking is not allowed.

                    <br>

                    <div class="container" style="padding:5px 10px;">
                        1) Cracking as in modifying the jar aka its contents.
                        <br>
                        2) Leaking as in spreading the client without permission.
                    </div>

                    2) Account Sharing is not allowed.

                    <br>

                    3) Refunding / Charge backs are not allowed.

                    <hr style="width:25%;">
                    <br>

                    <div class="container mb-0 mt-0" style="max-width:250px;">
                        <div class="pricing card-deck flex-column flex-md-row mb-3">
                            <div class="card card-pricing text-center px-3 mb-4">
                                <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white shadow-sm">Lifetime</span>
                                <div class="bg-transparent card-header pt-4 border-0">
                                    <h1 class="h1 font-weight-normal text-primary text-center mb-0"
                                        data-pricing-value="15">$<span
                                            class="price">10 USD</span></h1>
                                </div>
                                <div class="card-body pt-0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="width:25%;">

                    <div class="text-center" style="font-weight:bold;color:red;">
                        Breaking any of these terms will result in a permanent ban!
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a class="btn btn-primary" href="{{ url('payments/create') }}">Purchase</a>
                </div>

            </div>
        </div>
    </div>
@endsection
